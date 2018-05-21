package austragebuch;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;
import java.time.LocalDateTime;

/**
 * Klasse, um als Schnittstelle zu php-Skripten Aktionen in der Datenbank auszuführen
 */
public class Connector {
	
	/**
	 * Trägt einen Nutzer aus
	 * @param id TelegramID
	 * @param back Zeitpunkt, zu dem der Nutzer zurück sein sollte
	 * @param wohin Ort, an den der Nutzer ausgetragen ist
	 * @return Rückmeldung, ob das Austragen erfolgreich war
	 * @throws Exception Fehler bei der Verbindung zur Datenbank
	 */
    public static String austragen(Integer id, String back, String wohin) throws Exception {
        URL url = new URL("http://localhost/Austragebuch/bot/austragen.php?id=" + id + 
        		"&wohin=" + wohin + "&back=" + back);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        String inputLine;
        String out = "";

        while ((inputLine = in.readLine()) != null) 
            out += inputLine;
        in.close();
        return out;
    }
    
    /**
     * Überprüft Nutzerdaten
     * @param id TelegramID
     * @return aktuelle Daten über den Nutzer: Austrage-Status und registrierte ID
     * @throws Exception Fehler bei der Verbindung zur Datenbank
     */
    public static String checkUser(Integer id) throws Exception{
    	URL url = new URL("http://localhost/Austragebuch/bot/checkuser.php?id=" + id);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        String inputLine;
        String out = "";

        while ((inputLine = in.readLine()) != null) 
            out += inputLine;
        in.close();
        return out;
    }
    
    /**
     * Methode, um Nutzer in der Datenbank zurückzutragen
     * @param id TelegramID
     * @return Rückmeldung, ob das Zurücktragen erfolgreich war
     * @throws Exception Fehler bei der Verbindung zur Datenbank
     */
    public static String zurücktragen(Integer id) throws Exception {
        URL url = new URL("http://localhost/Austragebuch/bot/zuruecktragen.php?id=" + id);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        return in.readLine();
    }

    /**
     * Methode, die eine Nachricht zurückgibt, die alle Informationen über den letzten Eintrag des spezifizierten Nutzers enthält
     * @param id TelegramID
     * @return den letzten Eintrag in der Datenbank
     * @throws Exception Fehler bei der Verbindung zur Datenbank
     */
	public static String getEintragMessage(Integer id) throws Exception {
		URL url = new URL("http://localhost/Austragebuch/bot/geteintrag.php?id=" + id);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        
        return "Dein letzter Eintrag: \n" + in.readLine() + "\nWillst du dich wirklich zurücktragen?";
     
	}

	/**
	 * Methode, die alle aktuellen Pakete des spezifizierten Nutzers enthält
	 * @param id TelegramID
	 * @return Fehlermeldung oder Nachricht mit allen Paketen
	 * @throws Exception Fehler bei der Verbindung zur Datenbank
	 */
	public static String pakete(Integer id) throws Exception {
		URL url = new URL("http://localhost/Austragebuch/bot/pakete.php?id=" + id);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        String inputLine;
        String out = "";

        while ((inputLine = in.readLine()) != null) 
            out += inputLine + "\n";
        in.close();
        return out;
	}

	/**
	 * Methode, die aus einem String mit den Austragebuch.zulässigen Datumsformaten ein LocalDateTime-Objekt erstellt.
	 * @param back Zeitpunkt, der umgewandelt werden soll
	 * @throws Exception Fehler bei der Verbindung zur Datenbank und beim Übersetzen
	 */
	public static LocalDateTime dateFromString(String back) throws Exception {
		//Skript, welches das Datum in der Form Y-m-d H:i:s zurückgibt
		URL url = new URL("http://localhost/Austragebuch/bot/dateFromString.php?str=" + back);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        String inputLine = in.readLine();
        //Fehler bei der Formatierung
        if(inputLine.equals("Date error")){
        	System.err.println("Date format exception at input " + back);
        	throw new Exception();
        }
        String[] datetime = inputLine.split(" ");
        String date = datetime[0];
        String time = datetime[1];
        
        datetime = date.split("-");
        int year = Integer.parseInt(datetime[0]);
        int month = Integer.parseInt(datetime[1]);
        int day = Integer.parseInt(datetime[2]);
        
        datetime = time.split(":");
        int hour = Integer.parseInt(datetime[0]);
        int minute = Integer.parseInt(datetime[1]);
        
		return LocalDateTime.of(year, month, day, hour, minute);
	}

	/**
	 * 
	 * @param id TelegramID
	 * @return Ob der angegebene Nutzer Postdienst hat
	 * @throws Exception Fehler bei der Verbindung zur Datenbank
	 */
	public static boolean isPostdienst(Integer id) throws Exception {
		URL url = new URL("http://localhost/Austragebuch/bot/is_postdienst.php?id=" + id);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        String out = "false";
		try {
			out = in.readLine();
		} catch (IOException e) {
			e.printStackTrace();
		}
        return Boolean.parseBoolean(out);
	}

	/**
	 * Methode, um eine neues Paket in die Datenbank einzulesen
	 * @param uid TelegramID des Schülers, dessen Paket registriert wird
	 * @param ort Ort, an dem sich das Paket befindet
	 * @return Nachricht zur Rückmeldung an den Postdienst
	 * @throws Exception Fehler bei der Verbindung zur Datenbank
	 */
	public static String neuesPaket(String uid, String ort) throws Exception {
		URL url = new URL("http://localhost/Austragebuch/bot/neues_paket.php?schueler_uid=" + uid + "&ort=" + ort);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        return in.readLine();
	}

	/**
	 * Methode, um ein Paket als nicht mehr aktuell zu markieren
	 * @param id TelegramID
	 * @param paketId
	 * @return Rückmeldungsnachricht an den Nutzer
	 * @throws IOException Fehler bei der Verbindung zur Datenbank
	 * @throws CustomException Fehler durch Input, z.B. nicht registrierte ID
	 */
	public static String paketAktuell(Integer id, String paketId) throws IOException, CustomException {
		URL url = new URL("http://localhost/Austragebuch/bot/paket_aktuell.php?schueler_id=" + String.valueOf(id) + "&paket_id=" + paketId);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        String result = in.readLine();
        //unterschiedliche Fehlertypen zurückgeben
        if(result.equals("err: id")){
        	throw new CustomException(CustomException.id);
        } else if(result.equals("err: paket_id")){
        	throw new CustomException(CustomException.paket_id);
        } else if(result.equals("err: paket_id")){
        	throw new CustomException(CustomException.association);
        } else if(result.trim().equals("success")){
        	return "Dein Paket wurde erfolgreich als nicht mehr aktuell markiert";
        } else{
        	throw new CustomException("Error at paketAktuell with input " + String.valueOf(id) + " and " + paketId);
        }
        
	}
	
	/**
	 * Methode, um alle aktuellen Pakete auszulesen
	 * @return Array mit allen Paketen und den zugehörigen Telegram-IDs
	 * @throws Exception Fehler bei der Verbindung zur Datenbank
	 */
	public static String[] pakete() throws Exception{
		URL url = new URL("http://localhost/Austragebuch/bot/paketeNotification.php");
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        String result = in.readLine();
        return result.split("<br>");
	}
    
}