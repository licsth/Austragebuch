package austragebuch;

public class ErrMessage {
	
	public static int DATE_FORMAT = 1;
	public static int OTHER = 2;
	public static int USER = 3;
	public static int POSTDIENST = 4;
	public static int PAKETE = 5;
	public static int NEUES_PAKET = 6;
	public static int EXCEPTION = 7;
	private static String id = "518019117";

	public ErrMessage(Bot bot, int type, String in) {
		if(type == 1){
			bot.send(id, "AustragebuchBot:\nDate format exception at input: " + in);
		} else if(type == 2){
			bot.send(id, "AustragebuchBot:\nUnknown exception at input: " + in);
		} else if(type == 3){
			bot.send(id, "AustragebuchBot:\nError retrieving user information from user " + in);
		} else if(type == 4){
			bot.send(id, "AustragebuchBot:\nError getting postdienst from user " + in);
		} else if(type == 5){
			bot.send(id, "AustragebuchBot:\nError getting pakete from user " + in);
		} else if(type == 6){
			bot.send(id, "AustragebuchBot:\nError registering new paket with input: \n" + in);
		} else if(type == 7){
			bot.send(id,  in);
		}
	}

}
