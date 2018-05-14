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

public class Bot extends TelegramLongPollingBot {
	
	static String BOT_TOKEN = "Bitch you thought";
	static String BOT_USERNAME = "pick one yourself";
	
	HashMap<Integer, Integer> austragen = new HashMap<Integer, Integer>();
	HashMap<Integer, Boolean> zurücktragen = new HashMap<Integer, Boolean>();
	HashMap<Integer, Integer> postdienstProcess = new HashMap<Integer, Integer>();
	
	HashMap<Integer, String> where = new HashMap<Integer, String>();
	HashMap<Integer, String> paketuid = new HashMap<Integer, String>();
	
	HashMap<Integer, Boolean> erinnerung = new HashMap<Integer, Boolean>();
	HashMap<Integer, Boolean> postdienst = new HashMap<Integer, Boolean>();
	
	Keyboards kb = new Keyboards();
	
    private static final ScheduledExecutorService executorService = Executors.newScheduledThreadPool(1); ///< Thread to execute operations

	public Bot(){
					
		SendMessage message = new SendMessage() // Create a SendMessage object with mandatory fields
                .setChatId("518019117")
                .setReplyMarkup(kb.main)
                .setText("I'm back being great as ever! " + Emoji.random());
		try {
            execute(message); // Call method to send the message
        } catch (TelegramApiException e) {
            e.printStackTrace();
        }
		
		paketeNotification();
		
		
	}
	
	@Override
    public String getBotUsername() {
        return "pick one yourself";
    }

    @Override
    public String getBotToken() {
        return "Bitch you thought";
    }


	@Override
	public void onUpdateReceived(Update update) {
		String text = update.getMessage().getText().toLowerCase();
		Integer id = update.getMessage().getFrom().getId();
		
		//global access
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
		
		if(postdienstProcess.containsKey(id)){
			if(postdienstProcess.get(id) == 1){
				if(text.equals("/A") || text.equals("A") || text.equals("pakete einsehen")){
					meinePakete(update);
					postdienstProcess.put(id, 0);
				} else if(text.equals("/B") || text.equals("B") || text.equals("neues paket registrieren") || text.equals("Neues Paket hinzufügen")){
					postdienstProcess.put(id, 2);
					send(update, "Für wen ist das Paket? (Vorname Nachname oder vorname.nachname");
				}
				return;
			} else if(postdienstProcess.get(id) == 2){
				paketuid.put(id, text);
				postdienstProcess.put(id, 3);
				send(update, "Wo befindet sich das Paket?");
				return;
			} else if(postdienstProcess.get(id) == 3){
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
        	//ID
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
        	} else if(checkGod(update)) return;
        	else if(text.equals("danke") || text.equals("dankeschön") || text.equals("dankesehr") || text.equals("dankee") || text.equals("danke dir") || text.equals("vielen dank") || text.equals("dankeschöön")){
        		send(update, Replies.bittesehr(), kb.main);
        	}
        	//Default
        	else{
        		if(text.startsWith("/")){
	        		try{
	        			Integer.parseInt(text.substring(1));
	        			String paketId = text.substring(1);
	        			send(update, Connector.paketAktuell(id, paketId), kb.main);
	        			return;
	        		} catch (CustomException e) {
						if(e.type() == CustomException.id){
							id(update);
							return;
						} else if(e.type() == CustomException.paket_id){
							send(update, "Es konnte unter dieser Paket-ID kein Paket gefunden werden.", kb.main);
							return;
						} else if(e.type() == CustomException.paket_id){
							send(update, "Das Paket, dessen ID du angegeben hast, scheint nicht deins zu sein " + Emoji.CONFUSED_FACE.toString(), kb.main);
							return;
						} else if(e.type() == 0){
							send(update, "Irgendwas ist hier verdammt schief gelaufen... wir werden uns so bald wie möglich darum kümmern.", kb.main);
							new ErrMessage(this, ErrMessage.EXCEPTION, e.text());
						}
						return;
					} catch(Exception e2){
	        			
	        		} 
        		}
        		send(update, "Das habe ich leider nicht verstanden " + Emoji.FLUSHED_FACE.toString()
        		+ "\nWenn du Hilfe brauchst, klicke hier: /help", kb.main);
        		System.out.println("Nicht-erkannter Text: " + text);
        	}
		}
	}

	private boolean checkGod(Update update) {
		String text = update.getMessage().getText();
		Integer id = update.getMessage().getFrom().getId();
		
		if(id != 518019117) return false;
		if(text.length() < 11) return false;
		if(text.substring(0, 11).equals("sendmessage") || text.substring(0, 11).equals("Sendmessage")){
			String[] parts = text.split(" - ");
			Long dest = Long.parseLong(parts[1]);
			String content = parts[2];
			
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
		
		return false;
	}
	
	private void helpme(Update update) {
		String text = update.getMessage().getText();
		Integer id = update.getMessage().getFrom().getId();
		String name = update.getMessage().getFrom().getFirstName() + " " + update.getMessage().getFrom().getLastName();
		String uid = update.getMessage().getFrom().getUserName();
		
		String msg = text.replaceAll("helpme", "");
		msg = msg.replaceAll("Helpme", "");
		msg = msg.trim();
		
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

	private void neuesPaket(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		String uid = paketuid.get(id);
		uid = uid.replaceAll(" ", "%20");
		paketuid.remove(id);
		String ort = update.getMessage().getText();
		try {
			send(update, Connector.neuesPaket(uid, ort), kb.main);
		} catch (Exception e) {
			send(update, "Irgendwas ist schief gelaufen... wir werden uns so bald wie möglich darum kümmern " + Emoji.NERD.toString(), kb.main);
			new ErrMessage(this, ErrMessage.NEUES_PAKET, "uid: " + uid.replaceAll("%20", " ") + "\nort: " + ort + "\nby: " + id);
		}
		postdienstProcess.put(id, 0);
	}

	private void pakete(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		try {
			postdienst.put(id, Connector.isPostdienst(id));
		} catch (Exception e) {
			send(update, "Irgendwas ist schief gelaufen... wir werden uns so bald wie möglich darum kümmern " + Emoji.NERD.toString(), kb.main);
			new ErrMessage(this, ErrMessage.POSTDIENST, String.valueOf(id));
			return;
		}
		if(postdienst.get(id)){
			send(update, "Wähle eine Aktion: \n/A Pakete einsehen oder \n/B Neues Paket hinzufügen", kb.postdienst);
			postdienstProcess.put(id, 1);
		} else{
			meinePakete(update);
		}
		
	}

	private void meinePakete(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		send(update, "Im Folgenden werden deine aktuellen Pakete gelistet. Wenn du ein Paket bereits abgeholt hast, es also nicht mehr aktuell ist, klicke auf die Zahl hinter dem Paket.", kb.main);
		try {
			send(update, Connector.pakete(id), kb.main, "HTML");
		} catch (Exception e) {
			send(update, "Irgendwas ist schief gelaufen... wir werden uns so bald wie möglich darum kümmern.");
			new ErrMessage(this, ErrMessage.PAKETE, String.valueOf(id));
			return;
		}
	}

	private void formate(Update update) {
		send(update, "Im Folgenden eine Liste mit zulässigen Formaten (h steht für Stunde, m für Minute, d für Tag des Monats, mo für Monat):");
		send(update, "h (Uhr)\n"
				+ "h:m (Uhr)\n"
				+ "h.m (Uhr)\n"
				+ "d.mo.(,) [Uhrzeit wie oben]\n"
				+ "[Wochentag](,) [Uhrzeit wie oben]\n"
				+ "Morgen(,) [Uhrzeit wie oben]");
		
	}

	private void zurücktragen1(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		if(!checkZurücktragen(update)) return;
		zurücktragen.put(id, true);
		SendMessage message;
		try {
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
			new ErrMessage(this, ErrMessage.USER, String.valueOf(id));
		}
		
		
	}
	
	private void zurücktragen(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		if(update.getMessage().getText().equals("Nein")){
			send(update, "Okay, du wurdest nicht zurückgetragen " + Emoji.SHRUG.toString(), kb.main);
			zurücktragen.put(id, false);
			return;
		} else if(!(update.getMessage().getText().equals("Ja") || update.getMessage().getText().equals("ja"))){
			send(update, Emoji.POINTING_INDEX.toString() + "Das habe ich leider nicht verstanden", kb.bool);
			return;
		}
		
		zurücktragen.put(id, false);
		try {
			String result = Connector.zurücktragen(id);
			if(result.equals("success")){
				send(update, Emoji.BACK.toString() + " Du wurdest erfolgreich zurückgetragen!", kb.main);
			} else if(result.equals("err")){
				send(update, "Irgendwas ist schiefgelaufen...", kb.main);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		
	}


	private void austragen(Update update) {
		Integer id = update.getMessage().getFrom().getId();
		String wohin = where.get(id);
		String back = update.getMessage().getText();
		back = back.replaceAll(" ", "%20");
		wohin = wohin.replaceAll(" ", "%20");
		
		try {
			String result = Connector.austragen(id, back, wohin);
			if(result.equals("success")){
				if(wohin.equals("WB")) {
					send(update, "Okay, viel Spaß in den Weinbergen! " + Emoji.GRAPE.toString(), kb.main);
				}
				else {
					send(update, "Du wurdest erfolgreich ausgetragen " + Emoji.WAVING_HAND_SIGN.toString(), kb.main);
					where.remove(id);
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
					
				}
				austragen.put(id, 0);
			} else if(result.equals("Date error")){
				send(update, "Das Format konnte leider nicht erkannt werden...\n"
						+ "Wenn du Hilfe mit dem Format von Zeiten brauchst klicke hier: /formate");
				new ErrMessage(this, ErrMessage.DATE_FORMAT, back);
			} else{
				send(update, "Irgendwas ist hier verdammt schief gelaufen. Bitte kontaktiere @licsth " + Emoji.NERD.toString());
				new ErrMessage(this, ErrMessage.OTHER, back + " " + where);
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		
	}

	private void erinnerung(Integer chatId, String back) throws Exception {
		back = back.replaceAll(" ", "%20");
		LocalDateTime date = Connector.dateFromString(back);
		final Runnable taskWrapper = () -> {
			try {
				if(Connector.checkUser(chatId).equals("nicht ausgetragen")) return;
			} catch (Exception e1) {
				new ErrMessage(this, ErrMessage.USER, String.valueOf(chatId));
			}
            try {
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
        
		executorService.schedule(taskWrapper, Duration.between(LocalDateTime.now(), date).getSeconds(), TimeUnit.SECONDS);
	}
	
	private void paketeNotification() {
		LocalDateTime now = LocalDateTime.now();
		LocalDateTime date = LocalDateTime.of(now.getYear(), now.getMonth(), now.getDayOfMonth()+1, 18, 0);
		final Runnable taskWrapper = () -> {
			try {
				String[] msg = Connector.pakete();
				for(String message : msg){
					String[] parts = message.split(" ");
					String id = parts[0];
					message = "";
					for(int i = 1; i < parts.length; i++){
						message += parts[i] + " ";
					}
					send(id, message, kb.main, "HTML");
				}
			} catch (Exception e) {
				new ErrMessage(this, ErrMessage.EXCEPTION,"Failed to send paket notifications");
			}
			
			//paketeNotification();
			
			
        };
        
		executorService.schedule(taskWrapper, Duration.between(LocalDateTime.now(), date).getSeconds(), TimeUnit.SECONDS);
	}

	private void austragen1(Update update) {
		if (!checkAustragen(update)) return;
		send(update, "Wohin möchtest du ausgetragen werden?");
		austragen.put(update.getMessage().getFrom().getId(), 1);
		
	}


	private void austragen2(Update update) {
		where.put(update.getMessage().getFrom().getId(), update.getMessage().getText());
		send(update, "Wann wirst du wieder zurück sein?");
		austragen.put(update.getMessage().getFrom().getId(), 2);
	}
	
	private void ausgetragen(Update update) {
		send(update, "Scheint als bist du bereits ausgetragen...", kb.main);
		
	}
	
	private void nichtausgetragen(Update update) {
		send(update, "Scheint als bist du gar nicht ausgetragen...", kb.main);
	}
	
	private void id(Update update) {
		send(update, "Anscheinend ist dein Telegram-Account nicht im Austragebuch registriert.\n"
                		+ "Um den Austragebuch-Bot nutzen zu können, navigiere im digitalen Austragebuch zu dem Menüpunkt [dein Nutzername]>Telegram und gib dort folgende Nummer ein: \n<b>"
                		+ update.getMessage().getFrom().getId() + "</b>", kb.main, "HTML");
	}
	
	private boolean checkAustragen(Update update) {
		try {
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
		}
		return true;
		
	}
	
	private boolean checkZurücktragen(Update update) {
		try {
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
			send(update, "Es ist ein Fehler aufgetreten, bitte verusche es später nochmal.");
			return false;
		}
		return true;
		
		
	}
	
	private void austrageProcessor(Update update){
		if(austragen.get(update.getMessage().getFrom().getId()) == 1){
			austragen2(update);
		} else if(austragen.get(update.getMessage().getFrom().getId()) == 2){
			austragen(update);
		} 
	}

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