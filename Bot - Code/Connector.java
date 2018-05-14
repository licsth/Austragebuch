package austragebuch;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;
import java.time.LocalDateTime;

public class Connector {
	
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
    
    public static String zurücktragen(Integer id) throws Exception {
        URL url = new URL("http://localhost/Austragebuch/bot/zuruecktragen.php?id=" + id);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        return in.readLine();
    }

	public static String getEintragMessage(Integer id) throws Exception {
		URL url = new URL("http://localhost/Austragebuch/bot/geteintrag.php?id=" + id);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        
        return "Dein letzter Eintrag: \n" + in.readLine() + "\nWillst du dich wirklich zurücktragen?";
     
	}

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

	public static LocalDateTime dateFromString(String back) throws Exception {
		URL url = new URL("http://localhost/Austragebuch/bot/dateFromString.php?str=" + back);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        String inputLine = in.readLine();
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

	public static String neuesPaket(String uid, String ort) throws Exception {
		URL url = new URL("http://localhost/Austragebuch/bot/neues_paket.php?schueler_uid=" + uid + "&ort=" + ort);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        return in.readLine();
	}

	public static String paketAktuell(Integer id, String paketId) throws IOException, CustomException {
		URL url = new URL("http://localhost/Austragebuch/bot/paket_aktuell.php?schueler_id=" + String.valueOf(id) + "&paket_id=" + paketId);
        URLConnection conn = url.openConnection();
        BufferedReader in = new BufferedReader(
                                new InputStreamReader(
                                conn.getInputStream()));
        String result = in.readLine();
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