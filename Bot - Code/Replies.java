package austragebuch;

public class Replies {
	
	public static String bittesehr() {
		String[] bittesehr = {
			"Kein Problem " + Emoji.UPSIDE_DOWN_FACE.toString(), "Stets zu Diensten " + Emoji.SMILING_FACE_WITH_SMILING_EYES.toString(), "Immer wieder gerne " + Emoji.UPSIDE_DOWN_FACE.toString(), "Du brauchst dich nicht zu bedanken, das ist mein Job",
			"Als hätte ich eine Wahl gehabt " + Emoji.UNAMUSED_FACE.toString()
		};
		return bittesehr[(int) (Math.random()*bittesehr.length)];
	}

}
