package austragebuch;

public class CustomException extends Throwable{
	
	/**
	 * 
	 */
	private static final long serialVersionUID = -9056069740098119391L;
	public static int id = 1;
	public static int paket_id = 2;
	public static int association = 3;
	private int type;
	private String text;

	public CustomException(int type) {
		this.type = type;
		this.text = "";
	}
	
	public CustomException(String text){
		this.text = text;
		this.type=0;
	}
	
	public int type(){
		return type;
	}
	
	public String text(){
		return text;
	}

}
