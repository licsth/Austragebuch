package austragebuch;

/**
 * Klasse mit individuellen Fehlertypen
 */
public class CustomException extends Throwable{
	
	private static final long serialVersionUID = -9056069740098119391L;
	//Fehlertypen
	/**
	 * ID: TelegramID ist nicht in der Datenbank registriert
	 */
	public static int id = 1;
	/**
	 * Fehler: Es wurde kein Paket mit der angegebenen PaketID gefunden
	 */
	public static int paket_id = 2;
	/**
	 * Fehler: Die angegebene PaketID passt nicht zur angegebenen TelegramID
	 */
	public static int association = 3;
	
	private int type;
	private String text;

	public CustomException(int type) {
		this.type = type;
		this.text = "";
	}
	
	public CustomException(String text){
		this.text = text;
		this.type = 0;
	}
	
	public int type(){
		return type;
	}
	
	public String text(){
		return text;
	}

}
