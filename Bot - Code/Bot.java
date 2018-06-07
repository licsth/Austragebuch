package austragebuch;
import java.time.Duration;
import java.time.LocalDateTime;
import java.util.HashMap;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import org.telegram.telegrambots.api.methods.send.SendMessage;
import org.telegram.telegrambots.api.objects.Update;
import org.telegram.telegrambots.api.objects.replykeyboard.ReplyKeyboardMarkup;
import org.telegram.telegrambots.bots.TelegramLongPollingBot;
import org.telegram.telegrambots.exceptions.TelegramApiException;

/**
 * Telegram-Bot als Ergänzung zum digitalen Austragebuch des Hansenbergs
 * @author lindathelen
 *
 */
public class Bot extends TelegramLongPollingBot {
	
	//Autentifizierung für Telegram
	private static String BOT_TOKEN = „…“;
	private static String BOT_USERNAME = „…“;
	
	//HashMaps zum Zuordnen von Daten zu ChatIDs
	//Prozesse (z.B. Nachfragen beim austragen)
	private HashMap<Integer, Integer> austragen = new HashMap<Integer, Integer>();
	private HashMap<Integer, Boolean> zurücktragen = new HashMap<Integer, Boolean>();
	private HashMap<Integer, Integer> postdienstProcess = new HashMap<Integer, Integer>();
	
	//Zwischenspeicher für Daten in Prozessen
	private HashMap<Integer, String> where = new HashMap<Integer, String>();
	private HashMap<Integer, String> paketuid = new HashMap<Integer, String>();
	
	//globale Daten (Erinnerungen an/aus und Postdienst ja/nein)
	private HashMap<Integer, Boolean> erinnerung = new HashMap<Integer, Boolean>();
	private HashMap<Integer, Boolean> postdienst = new HashMap<Integer, Boolean>();
	
	//individuelle Tastaturen
	private Keyboards kb = new Keyboards();
	
	//führt geplante Handlungen aus
	//z.B. Erinnerungen zum Zurücktragen
    private static final ScheduledExecutorService executorService = Executors.newScheduledThreadPool(1); ///< Thread to execute operations

	public Bot(){
			
		//Nachricht an Creator senden: Bot wieder verfügbar
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId("518019117")
                .setReplyMarkup(kb.main)
                .setText("I'm back being great as ever! " + Emoji.random());
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
		
		//Start der täglichen Pakete-Nachrichten
		paketeNotification();
		
		
	}
	
	@Override
    public String getBotUsername() {
        return BOT_USERNAME;
    }

    @Override
    public String getBotToken() {
        return BOT_TOKEN;
    }

    /**
     * Methode zum Reagieren auf Nachrichten an den Austragebuch-Bot
     * Wird ausgeführt, sobald der Bot etwas empfängt
     * @param update Objekt, welches die Daten über Empfangenes enthält
     */
	@Override
	public void onUpdateReceived(Update update) {
		String text = update.getMessage().getText().toLowerCase();
		Integer id = update.getMessage().getFrom().getId();
		
		//Befehle, die immer erreichbar sein sollen: Hilfe und Exit
		if(text.equals("/help") || text.equals("help") || text.equals("hilfe")){
			austragen.put(id, 0);
			postdienstProcess.put(id, 0);
			zurücktragen.put(id, false);
			send(update, "Dies ist der AustragebuchBot. Er kann dich austragen und wieder zurücktragen.\n"
    				+ "Sende einfach 'Austragen' oder klicke hier auf /austragen, um dich auszutragen oder sende 'Zurücktragen' (bzw. klicke hier /zuruecktragen), um dich zurückzutragen.\n"
    				+ "Falls du sonstige Hilfe benötigst, wende dich an unsere kompetenten Ansprechpartner @licsth und @erinyen oder"
    				+ "send hier eine Nachricht beginnend mit 'Helpme'"
    					, kb.main);
    		return;
		} else if(text.equals("/zurück") || text.equals("zurück") || text.equals("exit") || text.equals("/exit")){
    		send(update, "Du befindest dich wieder im Home-Modus " + Emoji.HOUSE.toString(), kb.main);
    		austragen.put(id, 0);
    		postdienstProcess.put(id, 0);
    		zurücktragen.put(id, false);
    		return;
    	}
		
		//Austrage-Prozess
		if(!austragen.containsKey(id)){
			austragen.put(id, 0);
		} else if(austragen.get(id) != 0){
			if(text.equals("/formate") || text.equals("formate")){
        		formate(update);
        	}
        	else austrageProcessor(update);
			return;
		} 
		
		//Zurücktrage-Prozess
		if(!zurücktragen.containsKey(id)){
			zurücktragen.put(id, false);
		} else if(zurücktragen.get(id)){
			zurücktragen(update);
			return;
		} 
		
		//Postdienst-Prozess
		if(postdienstProcess.containsKey(id)){
			if(postdienstProcess.get(id) == 1){
				//erste Entscheidungsmöglichkeit: Pakete einsehen oder Paket eintragen
				if(text.equals("/A") || text.equals("A") || text.equals("pakete einsehen")){
					meinePakete(update);
					postdienstProcess.put(id, 0);
				} else if(text.equals("/B") || text.equals("B") || text.equals("neues paket registrieren") || text.equals("Neues Paket hinzufügen")){
					postdienstProcess.put(id, 2);
					send(update, "Für wen ist das Paket? (Vorname Nachname oder vorname.nachname");
				}
				return;
			} else if(postdienstProcess.get(id) == 2){
				//Paket eintragen: Einlesen des Schülers
				paketuid.put(id, text);
				postdienstProcess.put(id, 3);
				send(update, "Wo befindet sich das Paket?");
				return;
			} else if(postdienstProcess.get(id) == 3){
				//Paket eintragen: letzter Schritt: Paket in die Datenbank eintragen
				neuesPaket(update);
				return;
			} 
		}
		
		//Andere
		if (update.hasMessage() && update.getMessage().hasText()) {
        	
			//Beginn Austragen
        	if(text.equals("austragen")  || text.equals("/austragen")){
        		austragen1(update);
        	} 
        	//Beginn zurücktragen
        	else if(text.equals("zurücktragen") || text.equals("/zuruecktragen")){
        		zurücktragen1(update);
        	} 
        	//Beginn Konversation
        	else if(text.equals("/start") || text.equals("start")){
        		
        		send(update, "Hi, mein Name ist AustragebuchBot " + Emoji.SMILING_FACE_WITH_SMILING_EYES.toString() + "\n"
        				+ "Du kannst dich hier /austragen, /zuruecktragen oder deine /pakete einsehen.\n"
        				+ "Wenn du deine Telegram-ID noch nicht im Austragebuch registriert hast, klicke hier: /id \n"
        				+ "Sende jederzeit /exit um einen Prozess zu beenden.\n"
        				+ "Falls du Hilfe brauchst, sende /help oder frage einen unserer kompetenten Ansprechpartner (@licsth " + Emoji.NERD.toString()
        				+ " und @erinyen) oder schreibe hier eine Nachricht beginnend mit 'helpme'", kb.main);
        	} 
        	//ID registrieren
        	else if(text.equals("/id") || text.equals("id")){
        		send(update, "Um deine Telegram-ID zu registrieren, zu ändern oder zu löschen, besuche das digitale Austragebuch und navigiere dort zum Menüpunkt [dein Nutzername]>Telegram. Gib dort diese Nummer an: " + id
        					, kb.main);
        	} 
        	//Pakete
        	else if(text.equals("/pakete") || text.equals("pakete")){
        		pakete(update);
        	} 
        	//Formate
        	else if(text.equals("/formate") || text.equals("formate")){
        		formate(update);
        	} 
        	//Einstellungen
        	else if(text.equals("/einstellungen") || text.equals("einstellungen")){
        		send(update, "Du kannst mit /erinnerungen Erinnerungen fürs Zurücktragen aus- bzw. anstellen (toggle). Erinnerungen sind standardmäßig an.", kb.einstellungen);
        	} 
        	//Hilfe-Nachricht
        	else if(text.startsWith("/helpme") || text.startsWith("helpme")){
        		helpme(update);
        		send(update, "Deine Nachricht wurde an meine Schöpfer übermittelt.", kb.main);
        	} 
        	//Erinnerungen
        	else if(text.equals("/erinnerungen") || text.equals("erinnerungen")){
        		if(!erinnerung.containsKey(id)){
        			erinnerung.put(id, false);
        			send(update, "Erinnerungen wurden deaktiviert. Um Erinnerungen wieder zu aktivieren, gehe erneut auf /erinnerungen", kb.main);
        		} else if(erinnerung.get(id)){
        			erinnerung.put(id, false);
        			send(update, "Erinnerungen wurden deaktiviert. Um Erinnerungen wieder zu aktivieren, gehe erneut auf /erinnerungen", kb.main);
        		} else{
        			erinnerung.put(id, true);
        			send(update, "Erinnerungen wurden aktiviert. Um Erinnerungen wieder zu deaktivieren, gehe erneut auf /erinnerungen", kb.main);
        		}
        	} 
        	//Nachricht von Auftragebuch-Zuständigen
        	else if(checkGod(update)) return;
        	//Danke -> Bitte
        	else if(text.equals("danke") || text.equals("dankeschön") || text.equals("dankesehr") || text.equals("dankee") || text.equals("danke dir") || text.equals("vielen dank") || text.equals("dankeschöön")){
        		send(update, Replies.bittesehr(), kb.main);
        	}
        	//Andere
        	else{
        		//Paket als nicht mehr aktuell markieren (Schema /PaketID)
        		if(text.startsWith("/")){
	        		try{
	        			Integer.parseInt(text.substring(1));
	        			String paketId = text.substring(1);
	        			send(update, Connector.paketAktuell(id, paketId), kb.main);
	        			return;
	        		} catch (CustomException e) {
	        			//individuelle Fehlermeldungen
	        			//keine Telegram-ID gefunden
						if(e.type() == CustomException.id){
							id(update);
							return;
						} 
						//keine Paket-ID gefunden
						else if(e.type() == CustomException.paket_id){
							send(update, "Es konnte unter dieser Paket-ID kein Paket gefunden werden.", kb.main);
							return;
						} 
						//Paket gehört nicht zum Schüler
						else if(e.type() == CustomException.association){
							send(update, "Das Paket, dessen ID du angegeben hast, scheint nicht deins zu sein " + Emoji.CONFUSED_FACE.toString(), kb.main);
							return;
						} 
						//Andere
						else if(e.type() == 0){
							send(update, "Irgendwas ist hier verdammt schief gelaufen... wir werden uns so bald wie möglich darum kümmern.", kb.main);
							//Fehlermeldung wird an Zuständige gesendet
							new ErrMessage(this, ErrMessage.EXCEPTION, e.text());
						}
						return;
					} catch(Exception e2){
	        			//Fehler bei Integer.parseInt -> keine PaketID gegeben
	        		} 
        		}
        		//Default-Case: keine passende Antwort wurde gefunden
        		send(update, "Das habe ich leider nicht verstanden " + Emoji.FLUSHED_FACE.toString()
        		+ "\nWenn du Hilfe brauchst, klicke hier: /help", kb.main);
        		System.out.println("Nicht-erkannter Text: " + text);
        	}
		}
	}

	/**
	 * Methode, um zu prüfen, ob eine zu weiterleitende Nachricht vom Austragebuch-Zuständigen geschickt wurde.
	 * Leitet Nachrichten nach dem Schema "sendmessage - TelegramID - Nachricht" an spezifizierte Adressaten weiter.
	 * @param update Informationen über gesendete Nachricht
	 * @return Ob die Nachricht (update) eine weiterzuleitende Nachricht war
	 */
	private boolean checkGod(Update update) {
		String text = update.getMessage().getText();
		Integer id = update.getMessage().getFrom().getId();
		
		//Stammt die Nachricht vom Zuständigen?
		if(id != 518019117) return false;
		//Ist die Nachricht lang genug, um dem Schema zu entsprechen
		if(text.length() < 11) return false;
		//Beginnt die Nachricht dem Schema entsprechend?
		if(text.substring(0, 11).equals("sendmessage") || text.substring(0, 11).equals("Sendmessage")){
			String[] parts = text.split(" - ");
			//TelegramID des Adressaten an zweiter Stelle des Schemas
			Long dest = Long.parseLong(parts[1]);
			//Text an dritter Stelle des Schemas
			String content = parts[2];
			
			//Nachricht weiterleiten
			SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
	                .setChatId(dest)
	                .setReplyMarkup(kb.main)
	                .setText(content);
			try {
	            execute(message); // Call method to send the message
	        } catch (TelegramApiException e) {
	            e.printStackTrace();
	        }
			
    		return true;
		}
		//Nachricht entspricht nicht dem Schema
		return false;
	}
	
	/**
	 * Leitet Nachricht von Nutzern an Zuständige zur Fehlerbehebung weiter.
	 * @param update Informationen über empfangene Nachricht
	 */
	private void helpme(Update update) {
		//Text der Nachricht
		String text = update.getMessage().getText();
		//Daten über Sender
		Integer id = update.getMessage().getFrom().getId();
		String name = update.getMessage().getFrom().getFirstName() + " " + update.getMessage().getFrom().getLastName();
		String uid = update.getMessage().getFrom().getUserName();
		
		//Text formatieren
		String msg = text.replaceAll("helpme", "");
		msg = msg.replaceAll("Helpme", "");
		msg = msg.trim();
		
		//Nachricht an Zuständigen weiterleiten
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId("518019117")
                .setReplyMarkup(kb.main)
                .setText(String.valueOf(id) + " (" + name + ") " + "(" + uid + ") braucht Hilfe: \n" + msg);
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
			
	}

	/**
	 * Registriert Pakete in der Datenbank
	 * @param update Informationen über gesendete Nachricht
	 */
	private void neuesPaket(Update update) {
		//Schüler, für den das Paket ist, speichern
		Integer id = update.getMessage().getFrom().getId();
		String uid = paketuid.get(id);
		//Zum Transfer Leerzeichen ersetzen
		uid = uid.replaceAll(" ", "%20");
		//Daten aus der HashMap löschen
		paketuid.remove(id);
		//Ort des Pakets speichern
		String ort = update.getMessage().getText();
		try {
			//Paket in die Datenbank eintragen, Rückmeldung schicken
			send(update, Connector.neuesPaket(uid, ort), kb.main);
		} catch (Exception e) {
			//falls ein Fehler aufgetreten ist:
			//Rückmeldung an Nutzer schicken
			send(update, "Irgendwas ist schief gelaufen... wir werden uns so bald wie möglich darum kümmern " + Emoji.NERD.toString(), kb.main);
			//Nachricht an Zuständigen schicken
			new ErrMessage(this, ErrMessage.NEUES_PAKET, "uid: " + uid.replaceAll("%20", " ") + "\nort: " + ort + "\nby: " + id);
		}
		//Postdienst-Prozess beenden
		postdienstProcess.put(id, 0);
	}

	/**
	 * Führt Aktionen rund um Pakete aus
	 * @param update Informationen über die empfangene Nachricht
	 */
	private void pakete(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		try {
			//Überprüfen, ob der Nutzer vom Postdienst ist
			postdienst.put(id, Connector.isPostdienst(id));
		} catch (Exception e) {
			//falls ein Fehler aufgetreten ist:
			//Rückmeldung an Nutzer
			send(update, "Irgendwas ist schief gelaufen... wir werden uns so bald wie möglich darum kümmern " + Emoji.NERD.toString(), kb.main);
			//Fehlermeldung an Zuständigen schicken
			new ErrMessage(this, ErrMessage.POSTDIENST, String.valueOf(id));
			return;
		}
		if(postdienst.get(id)){
			//Für den Postdienst: Wahlmöglichkeit Pakete einsehen/registrieren
			send(update, "Wähle eine Aktion: \n/A Pakete einsehen oder \n/B Neues Paket hinzufügen", kb.postdienst);
			postdienstProcess.put(id, 1);
		} else{
			//neue Pakete anzeigen
			meinePakete(update);
		}
		
	}

	/**
	 * Zeigt aktuelle Pakete eines Nutzers an
	 * @param update Informationen über die empfangene Nachricht
	 */
	private void meinePakete(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		//Information über folgende Nachrichten
		send(update, "Im Folgenden werden deine aktuellen Pakete gelistet. Wenn du ein Paket bereits abgeholt hast, es also nicht mehr aktuell ist, klicke auf die Zahl hinter dem Paket.", kb.main);
		try {
			//Pakete aus der Datenbank auslesen und an Nutzer senden
			send(update, Connector.pakete(id), kb.main, "HTML");
		} catch (Exception e) {
			//falls ein Fehler aufgetreten ist:
			//Rückmeldung an Nutzer
			send(update, "Irgendwas ist schief gelaufen... wir werden uns so bald wie möglich darum kümmern.");
			//Fehlermeldung an Zuständigen schicken
			new ErrMessage(this, ErrMessage.PAKETE, String.valueOf(id));
			return;
		}
	}

	/**
	 * Sendet alle gültigen Datumsformate (z.B. zum Austragen) an einen Nutzer
	 * @param update Informationen über die empfangene Nachricht
	 */
	private void formate(Update update) {
		//Nachricht senden
		send(update, "Im Folgenden eine Liste mit zulässigen Formaten (h steht für Stunde, m für Minute, d für Tag des Monats, mo für Monat):");
		send(update, "h (Uhr)\n"
				+ "h:m (Uhr)\n"
				+ "h.m (Uhr)\n"
				+ "d.mo.(,) [Uhrzeit wie oben]\n"
				+ "[Wochentag](,) [Uhrzeit wie oben]\n"
				+ "Morgen(,) [Uhrzeit wie oben]");
		
	}

	/**
	 * Erser Schritt beim Zurücktragen
	 * @param update Informationen über die empfangene Nachricht
	 */
	private void zurücktragen1(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		//Test: kann sich der Nutzer aktuell zurücktragen?
		if(!checkZurücktragen(update)) return;
		//Zurücktrage-Prozess
		zurücktragen.put(id, true);
		SendMessage message;
		try {
			//Nachricht mit letztem Eintrag senden
			message = new SendMessage() // Create a SendMessage object with mandatory fields
			        .setChatId(String.valueOf(id))
			        .setReplyMarkup(kb.bool)
			        .setParseMode("HTML")
			        .setText(Connector.getEintragMessage(id));
			try {
	            execute(message); // Call method to send the message
	        } catch (TelegramApiException e) {
	            e.printStackTrace();
	        }
		} catch (Exception e1) {
			//Fehlermeldung: Nutzerdaten konnten nicht ausgelesen werden
			new ErrMessage(this, ErrMessage.USER, String.valueOf(id));
		}
		
		
	}
	
	/**
	 * Letzter Schritt zum Zurücktragen
	 * @param update Informationen über die empfangene Nachricht
	 */
	private void zurücktragen(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		//Je nach Antwort (Ja/Nein) Nutzer (nicht) zurücktragen
		if(update.getMessage().getText().equals("Nein")){
			send(update, "Okay, du wurdest nicht zurückgetragen " + Emoji.SHRUG.toString(), kb.main);
			zurücktragen.put(id, false);
			return;
		} 
		//Falls weder Ja noch Nein geantwortet wurde
		else if(!(update.getMessage().getText().equals("Ja") || update.getMessage().getText().equals("ja"))){
			send(update, Emoji.POINTING_INDEX.toString() + "Das habe ich leider nicht verstanden", kb.bool);
			return;
		}
		
		//Zurücktrage-Prozess beenden
		zurücktragen.put(id, false);
		//Zurücktragen
		try {
			String result = Connector.zurücktragen(id);
			//Rückmeldung an Nutzer
			if(result.equals("success")){
				send(update, Emoji.BACK.toString() + " Du wurdest erfolgreich zurückgetragen!", kb.main);
			} else if(result.equals("err")){
				send(update, "Irgendwas ist schiefgelaufen...", kb.main);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		
	}

	/**
	 * Trägt einen Nutzer aus
	 * @param update Informationen über die empfangene Nachricht
	 */
	private void austragen(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		//Daten über den Eintrag
		String wohin = where.get(id);
		String back = update.getMessage().getText();
		//Zum Transfer Leerzeichen ersetzen
		back = back.replaceAll(" ", "%20");
		wohin = wohin.replaceAll(" ", "%20");
		
		try {
			//Zurücktragen
			String result = Connector.austragen(id, back, wohin);
			//Rückmeldung an Nutzer
			if(result.equals("success")){
				if(wohin.equals("WB")) {
					send(update, "Okay, viel Spaß in den Weinbergen! " + Emoji.GRAPE.toString(), kb.main);
				}
				else {
					send(update, "Du wurdest erfolgreich ausgetragen " + Emoji.WAVING_HAND_SIGN.toString(), kb.main);
				}
				where.remove(id);
				//Erinnerung erstellen
				try{
					if(!erinnerung.containsKey(id)){
						erinnerung.put(id, true);
					}
					if(!erinnerung.get(id)) return;
					erinnerung(id, back);
					send(update, "Erinnerung zum Zurücktragen wurde erstellt " + Emoji.SMILING_FACE_WITH_SMILING_EYES.toString(), kb.main);
				} catch(Exception e){
					send(update, "Die Erinnerung konnte nicht erstellt werden " + Emoji.CONFUSED_FACE.toString(), kb.main);
				}
				austragen.put(id, 0);
			} else if(result.equals("Date error")){
				//Format konnte nicht erkannt werden
				send(update, "Das Format konnte leider nicht erkannt werden...\n"
						+ "Wenn du Hilfe mit dem Format von Zeiten brauchst klicke hier: /formate");
				//Nachricht an Zuständige, falls Formateliste ergänzt werden sollte
				new ErrMessage(this, ErrMessage.DATE_FORMAT, back);
			} else{
				//Fehlermeldungen
				send(update, "Irgendwas ist hier verdammt schief gelaufen. Bitte kontaktiere @licsth " + Emoji.NERD.toString());
				new ErrMessage(this, ErrMessage.OTHER, back + " " + where);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		
	}

	/**
	 * Erstellt eine Erinnerung zum Zurücktragen
	 * @param chatId TelegramID des entsprechenden Nutzers
	 * @param back Zeitpunkt, zu dem der Nutzer zurück sein sollte
	 * @throws Exception Fehler beim Datenformat
	 */
	private void erinnerung(Integer chatId, String back) throws Exception {
		//Zum Transfer Leerzeichen ersetzen
		back = back.replaceAll(" ", "%20");
		//angegebenen Zeitpunkt in DateTime übersetzen
		LocalDateTime date = Connector.dateFromString(back);
		final Runnable taskWrapper = () -> {
			//Test: ist der Nutzer noch ausgetragen?
			try {
				if(Connector.checkUser(chatId).equals("nicht ausgetragen")) return;
			} catch (Exception e1) {
				new ErrMessage(this, ErrMessage.USER, String.valueOf(chatId));
			}
            try {
            	//Wenn der Nutzer noch ausgetragen ist: Erinnerung senden
            	SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                        .setChatId(String.valueOf(chatId))
                        .setText("Du solltest mittlerweile zurückgetragen sein.");
        		try {
                    execute(message); // Call method to send the message
                } catch (TelegramApiException e) {
                    e.printStackTrace();
                }
            } catch (Exception e) {
            	
            }
        };
        //Task erstellen
		executorService.schedule(taskWrapper, Duration.between(LocalDateTime.now(), date).getSeconds(), TimeUnit.SECONDS);
	}
	
	/**
	 * Tägliche Pakete-Benachrichtigungen
	 */
	private void paketeNotification() {
		LocalDateTime now = LocalDateTime.now();
		//Tägliche Benchrigung um 18 Uhr
		LocalDateTime date = LocalDateTime.of(now.getYear(), now.getMonth(), now.getDayOfMonth()+1, 18, 0);
		final Runnable taskWrapper = () -> {
			try {
				//Alle neuen Pakete in einem Array
				String[] msg = Connector.pakete();
				for(String message : msg){
					//Für jedes Paket eine Nachricht senden
					//Formatierung der Informationen nach dem Schema "TelegramID Nachricht"
					String[] parts = message.split(" ");
					String id = parts[0];
					message = "";
					for(int i = 1; i < parts.length; i++){
						message += parts[i] + " ";
					}
					message = message.trim();
					//Benachrichtigung senden
					send(id, message, kb.main, "HTML");
				}
			} catch (Exception e) {
				//Fehlermeldung an Zuständigen, falls Benachrichtigung nicht gesendet werden konnte
				new ErrMessage(this, ErrMessage.EXCEPTION,"Failed to send paket notifications");
			}
			
			//rekursiver Aufruf der Methode
			paketeNotification();
			
			
        };
        
        //Task erstellen
		executorService.schedule(taskWrapper, Duration.between(LocalDateTime.now(), date).getSeconds(), TimeUnit.SECONDS);
	}

	/**
	 * Erster Schritt im Austrage-Prozess
	 * @param update Informationen über empfangene Nachricht
	 */
	private void austragen1(Update update) {
		//Test: kann der Nutzer aktuell ausgetragen werden)
		if (!checkAustragen(update)) return;
		//Nachfrage: Ort
		send(update, "Wohin möchtest du ausgetragen werden?");
		austragen.put(update.getMessage().getFrom().getId(), 1);
		
	}

	/**
	 * Zweiter Schritt im Austrage-Prozess
	 * @param update Informationen über empfangene Nachricht
	 */
	private void austragen2(Update update) {
		//Ort speichern
		where.put(update.getMessage().getFrom().getId(), update.getMessage().getText());
		//Nachfrage: Zeitpunkt zurück
		send(update, "Wann wirst du wieder zurück sein?");
		austragen.put(update.getMessage().getFrom().getId(), 2);
	}
	
	/**
	 * Sendet eine Nachricht, falls ein ausgetragener Nutzer versucht, sich auszutragen
	 * @param update Informationen über empfangene Nachricht
	 */
	private void ausgetragen(Update update) {
		send(update, "Scheint als bist du bereits ausgetragen...", kb.main);
		
	}
	
	/**
	 * Sendet eine Nachricht, falls ein nicht ausgetragener Nutzer versucht, sich zurückzutragen
	 * @param update Informationen über empfangene Nachricht
	 */
	private void nichtausgetragen(Update update) {
		send(update, "Scheint als bist du gar nicht ausgetragen...", kb.main);
	}
	
	/**
	 * Sendet eine Nachricht, falls ein Nutzer, dessen TelegramID nicht in der Datenbank registriert ist, versucht, den Austragebuch-Bot zu nutzen
	 * @param update Informationen über empfangene Nachricht
	 */
	private void id(Update update) {
		//Anleitung zum Registrieren der TelegramID
		send(update, "Anscheinend ist dein Telegram-Account nicht im Austragebuch registriert.\n"
                		+ "Um den Austragebuch-Bot nutzen zu können, navigiere im digitalen Austragebuch oben rechts zu dem Menüpunkt [dein Nutzername]>Telegram und gib dort folgende Nummer ein: \n<b>"
                		+ update.getMessage().getFrom().getId() + "</b>", kb.main, "HTML");
	}
	
	/**
	 * Überprüft, ob ein Nutzer ausgetragen werden kann.
	 * Kriterien: Ist der Nutzer aktuell ausgetragen? Ist die TelegramID registriert?
	 * @param update Informationen über empfangene Nachricht
	 * @return Ob der Nutzer ausgetragen werden kann
	 */
	private boolean checkAustragen(Update update) {
		try {
			//Methode checkUser, um Informationen über den Nutzer auszuwerten
			if(Connector.checkUser(update.getMessage().getFrom().getId()).equals("ausgetragen")){
				ausgetragen(update);
				return false;
			} else if(Connector.checkUser(update.getMessage().getFrom().getId()).equals("err: id")){
				id(update);
				if(postdienst.containsKey(update.getMessage().getFrom().getId())) postdienst.remove(update.getMessage().getFrom().getId());
				return false;
			}
		} catch (Exception e1) {
			e1.printStackTrace();
			return false;
		}
		return true;
		
	}
	
	/**
	 * Überprüft, ob ein Nutzer zurückgetragen werden kann.
	 * Kriterien: Ist der Nutzer aktuell ausgetragen? Ist die TelegramID registriert?
	 * @param update Informationen über empfangene Nachricht
	 * @return Ob der Nutzer zurücktragen werden kann
	 */
	private boolean checkZurücktragen(Update update) {
		try {
			//Methode checkUser, um Informationen über den Nutzer auszuwerten
			String check = Connector.checkUser(update.getMessage().getFrom().getId());
			if(check.equals("err: id")){
				id(update);
				if(postdienst.containsKey(update.getMessage().getFrom().getId())) postdienst.remove(update.getMessage().getFrom().getId());
				return false;
			} else if(check.equals("nicht ausgetragen")){
				nichtausgetragen(update);
				return false;
			}
		} catch (Exception e2) {
			send(update, "Es ist ein Fehler aufgetreten, bitte versuche es später nochmal.");
			return false;
		}
		return true;
		
		
	}
	
	/**
	 * Umgang mit Austrage-Prozess
	 * @param update Informationen über empfangene Nachricht
	 */
	private void austrageProcessor(Update update){
		// Methode zum jeweiligen Schritt im Prozess aufrufen
		if(austragen.get(update.getMessage().getFrom().getId()) == 1){
			austragen2(update);
		} else if(austragen.get(update.getMessage().getFrom().getId()) == 2){
			austragen(update);
		} 
	}

	//Methoden zum Senden von Nachrichten mit verschiedenen Parametern
	
	public void send(Update update, String text){
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId(String.valueOf(update.getMessage().getFrom().getId()))
                .setText(text);
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
	}
	
	public void send(String id, String text){
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId(id)
                .setText(text);
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
	}
	
	public void send(Update update, String text, ReplyKeyboardMarkup keyboard){
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId(String.valueOf(update.getMessage().getFrom().getId()))
                .setReplyMarkup(keyboard)
                .setText(text);
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
	}
	
	public void send(Update update, String text, ReplyKeyboardMarkup keyboard, String parseMode){
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId(String.valueOf(update.getMessage().getFrom().getId()))
                .setReplyMarkup(keyboard)
                .setParseMode(parseMode)
                .setText(text);
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
	}
	
	public void send(Update update, String text, String parseMode){
		Integer id = update.getMessage().getFrom().getId();
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId(String.valueOf(id))
                .setParseMode(parseMode)
                .setText(text);
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
	}
	
	public void send(String id, String text, String parseMode){
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId(id)
                .setParseMode(parseMode)
                .setText(text);
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
	}
	
	public void send(String id, String text, ReplyKeyboardMarkup keyboard){
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId(id)
                .setReplyMarkup(keyboard)
                .setText(text);
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
	}
	
	public void send(String id, String text, ReplyKeyboardMarkup keyboard, String parseMode){
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId(String.valueOf(id))
                .setReplyMarkup(keyboard)
                .setParseMode(parseMode)
                .setText(text);
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
	}
	
    
}