package austragebuch;

import java.util.ArrayList;

import org.telegram.telegrambots.api.objects.replykeyboard.ReplyKeyboardMarkup;
import org.telegram.telegrambots.api.objects.replykeyboard.buttons.KeyboardButton;
import org.telegram.telegrambots.api.objects.replykeyboard.buttons.KeyboardRow;

public class Keyboards {
	
	ReplyKeyboardMarkup main = new ReplyKeyboardMarkup();
	ReplyKeyboardMarkup bool = new ReplyKeyboardMarkup();
	ReplyKeyboardMarkup einstellungen = new ReplyKeyboardMarkup();
	ReplyKeyboardMarkup postdienst = new ReplyKeyboardMarkup();
	

	public Keyboards() {
		setup();
	}

	private void setup() {
		ArrayList<KeyboardRow> keyboard = new ArrayList<KeyboardRow>();
		KeyboardRow row = new KeyboardRow();
		KeyboardRow row2 = new KeyboardRow();
		KeyboardRow row5 = new KeyboardRow();
		KeyboardRow row21 = new KeyboardRow();
		
		ArrayList<KeyboardRow> keyboard2 = new ArrayList<KeyboardRow>();
		KeyboardRow row3 = new KeyboardRow();
		KeyboardRow row4 = new KeyboardRow();
		
		row.add(new KeyboardButton().setText("Austragen"));
		//row.add(new KeyboardButton().setText("B"));
		row2.add(new KeyboardButton().setText("Zurücktragen"));
		row5.add(new KeyboardButton().setText("Pakete"));
		row21.add(new KeyboardButton().setText("Einstellungen"));
		keyboard.add(row);
		keyboard.add(row2);
		keyboard.add(row5);
		keyboard.add(row21);
		main.setKeyboard(keyboard);
		main.setOneTimeKeyboard(true);
		
		row3.add(new KeyboardButton().setText("Ja"));
		//row.add(new KeyboardButton().setText("B"));
		row4.add(new KeyboardButton().setText("Nein"));
		//row2.add(new KeyboardButton().setText("D"));
		keyboard2.add(row3);
		keyboard2.add(row4);
		bool.setKeyboard(keyboard2);
		bool.setOneTimeKeyboard(true);
		
		ArrayList<KeyboardRow> keyboard3 = new ArrayList<KeyboardRow>();
		KeyboardRow row6 = new KeyboardRow();
		KeyboardRow row7 = new KeyboardRow();
		
		row6.add(new KeyboardButton().setText("Erinnerungen"));
		row7.add(new KeyboardButton().setText("Zurück"));
		keyboard3.add(row6);
		keyboard3.add(row7);
		einstellungen.setKeyboard(keyboard3);
		einstellungen.setOneTimeKeyboard(true);
		einstellungen.setResizeKeyboard(true);
		
		ArrayList<KeyboardRow> keyboard4 = new ArrayList<KeyboardRow>();
		KeyboardRow row8 = new KeyboardRow();
		KeyboardRow row9 = new KeyboardRow();
		
		row8.add(new KeyboardButton().setText("Pakete einsehen"));
		row9.add(new KeyboardButton().setText("Neues Paket registrieren"));
		keyboard4.add(row8);
		keyboard4.add(row9);
		postdienst.setKeyboard(keyboard4);
		postdienst.setOneTimeKeyboard(true);
		
	}
	
	public static ReplyKeyboardMarkup toKeyboard(String[][] buttons){
		ArrayList<KeyboardRow> keyboard = new ArrayList<KeyboardRow>();
		for(String[] row : buttons){
			KeyboardRow kr = new KeyboardRow();
			for(String btn : row){
				kr.add(new KeyboardButton().setText(btn));
			}
		}
		ReplyKeyboardMarkup kb = new ReplyKeyboardMarkup();
		kb.setKeyboard(keyboard);
		return kb;
	}

}
