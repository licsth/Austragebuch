package main;
import javax.swing.*;

import java.awt.*;
import java.awt.event.*;

public class Snake extends JFrame implements KeyListener{

  private static final long serialVersionUID = 1L;

  Container cp = getContentPane();
  
  JLabel[][] playGround = new JLabel[40][35]; 
  
  JButton neu = new JButton("Los");
  
  int punkte;
  JLabel spielStand = new JLabel("", JLabel.CENTER);
  
  JLabel gameover = new JLabel("GAME OVER", JLabel.CENTER);
  
  Timer timer, sTimer, exitWait, exitWait2;
  
  int length = 3;
  int maxLength = 50;
  double speed = 5;
  int speedI;
  
  int xR = (int)Math.floor(Math.random()*28 + 4);
  int yR = (int)Math.floor(Math.random()*33 + 1);
  
  int xP, yP;
  
  int[] xS;
  int[] yS;
    
  boolean rechts = true;
  boolean links = false;
  boolean oben = false;
  boolean unten = false;
  
  JCheckBox tempoChange = new JCheckBox("Beschleunigen");
  JCheckBox maxL = new JCheckBox("Maximale Länge");
  JCheckBox diff = new JCheckBox("Durch Wände gehen");
  
  JRadioButtonMenuItem slow = new JRadioButtonMenuItem("Langsam");
  JRadioButtonMenuItem mid = new JRadioButtonMenuItem("Mittel");
  JRadioButtonMenuItem fast = new JRadioButtonMenuItem("Schnell");
  ButtonGroup geschwindigkeit = new ButtonGroup();
  
  JMenu optionen = new JMenu("Optionen");
  JMenu geschw = new JMenu("Geschwindigkeit");
  JMenu hilfe = new JMenu("Hilfe");
  JMenuBar bar = new JMenuBar();
  
  JMenuItem anleitung = new JMenuItem("Anleitung");
  String anl = "<html>Ziel ist es, so viele rote Punkte wie möglich zu essen. <br> "
  		+ "Nutze die Pfeiltasten, um die Snake zu steuern. <br>"
  		+ "Die Snake darf nicht in sich selbst hineinlaufen.</html>";
  
  boolean velChange = true;
  boolean max = false;
  boolean diffundieren = true;
  boolean schnellPfeil = true;
  
  public Snake(){
    
    setSize(400,400);
    setTitle("Snake");
    setLocationRelativeTo(null);
    setContentPane(cp);
    setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    setResizable(false);
    setFocusable(true);
    
    if(max){
    	xS = new int[maxLength];
    	yS = new int[maxLength];
    }
    else if (!max){
    	xS = new int[300];
    	yS = new int[300];
    }
    
    optionen.add(tempoChange);
    tempoChange.setSelected(true);
    optionen.add(maxL);
    optionen.add(diff);
    diff.setSelected(true);
    bar.add(optionen);
    
    geschwindigkeit.add(slow);
    geschwindigkeit.add(mid);
    geschwindigkeit.add(fast);
    mid.setSelected(true);
    
    
    slow.addActionListener(new ActionListener(){

		@Override
		public void actionPerformed(ActionEvent e) {
			if(slow.isSelected()){
				speed = 2.5;
			}
		}
    	
    });
    
    mid.addActionListener(new ActionListener(){
    	public void actionPerformed(ActionEvent e){
    		if(mid.isSelected()){
    			speed = 5;
    		}
    	}
    });
    
    fast.addActionListener(new ActionListener(){
    	public void actionPerformed(ActionEvent e){
    		if(fast.isSelected()){
    			speed = 8;
    		}
    	}
    });
    
    geschw.add(slow);
    geschw.add(mid);
    geschw.add(fast);
    bar.add(geschw);
    
    hilfe.add(anleitung);
    anleitung.addActionListener(new ActionListener(){

		@Override
		public void actionPerformed(ActionEvent e) {
			JOptionPane.showConfirmDialog(null, anl, "Anleitung", JOptionPane.DEFAULT_OPTION);
		}
    	
    });
    
    bar.add(hilfe);
    
    setJMenuBar(bar);
    
    tempoChange.addActionListener(new ActionListener(){

		@Override
		public void actionPerformed(ActionEvent e) {
			velChange = tempoChange.isSelected();
		}
    	
    });
    
    maxL.addActionListener(new ActionListener(){

		@Override
		public void actionPerformed(ActionEvent evt) {
			if(maxL.isSelected()){
				String i = JOptionPane.showInputDialog("Maximale Länge bis Spielgewinn (min. 4): ");
				int m = Integer.parseInt(i);
				if(m<4){
					m = 4;
				}
				max = true;
				maxLength = m;
			}
		}
    	
    });
    
    diff.addActionListener(new ActionListener(){

		@Override
		public void actionPerformed(ActionEvent e) {
			diffundieren = diff.isSelected();
			if(!diffundieren){
				anl = "<html>Ziel ist es, so viele rote Punkte wie möglich zu essen. <br> "
				  		+ "Nutze die Pfeiltasten, um die Snake zu steuern. <br>"
				  		+ "Die Snake darf nicht in sich selbst <br>oder die Wände hineinlaufen.</html>";
			}
			else if(diffundieren){
				anl = "<html>Ziel ist es, so viele rote Punkte wie möglich zu essen. <br> "
				  		+ "Nutze die Pfeiltasten, um die Snake zu steuern. <br>"
				  		+ "Die Snake darf nicht in sich selbst hineinlaufen.</html>";
			}
		}
    	
    });
    
    cp.setLayout(null);
    cp.setBackground(Color.black);
    generatePlayground(Color.darkGray);
    
    gameover.setBounds(150, 175, 100, 40);
    cp.add(gameover);
    
    for(int i = 0; i<length; i++){
      xS[i] = xR - i;
      yS[i] = yR;
    }
    
    neu.setBounds(10, 10, 60, 20);
    neu.addActionListener(new ActionListener(){
    
    @Override
    public void actionPerformed(ActionEvent e) {
    	spiel();
    }
    
    });
    cp.add(neu);
    neu.setFocusPainted(true);
    neu.addKeyListener(this);
    cp.add(spielStand);
    
    setVisible(true);
  }
  
  public boolean collidesSelf(){
    boolean a = false;
    for(int i = 0; i<length; i++){
      for(int j = 1; j<length; j++){
        if(xS[i] == xS[(i+j) % length] && yS[(i+j) % length] == yS[i]){
          a = true;
        }
      }
    }
    return a;
  }
  
  public boolean collidesWell(){
    boolean a = false;
    if(xS[0] == 0 || xS[0] == 39 || yS[0] == 0 || yS[0] == 34){
      a = true;
    }
    return a;
  }
  
  public void speedUpdate(double s){
	  speedI = (int)Math.round(1000/s);
	  timer.setDelay(speedI);
  }

  public void spiel(){
    spielstand();
    neuerPunkt();
    timer = new Timer(speedI, new ActionListener(){
    
	    @Override
	    public void actionPerformed(ActionEvent e) {
		    //bestehende Punkte außer erstem verschieben und entfärben
		    for(int i = length; i>0; i--){
		    xS[i] = xS[i-1];
		    yS[i] = yS[i-1];
		    playGround[xS[i]][yS[i]].setBackground(Color.darkGray);
		    }
		    
		    //ersten Punkt neu festlegen
		    if(rechts){
		    	xS[0] = xS[1] + 1;
		    	yS[0] = yS[1];
		    }
		    
		    if(links){
		    xS[0] = xS[1] - 1;
		    yS[0] = yS[1];
		    }
		    
		    if(oben){
		    xS[0] = xS[1];
		    yS[0] = yS[1] - 1;
		    }
		    
		    if(unten){
		    xS[0] = xS[1];
		    yS[0] = yS[1] + 1;
		    }
		    
		    if(diffundieren && collidesWell() && rechts){
	    		xS[0] -= 38;
	    	}
		    else if(diffundieren && collidesWell() && unten){
		    	yS[0] -= 33;
		    }
		    else if(diffundieren && collidesWell() && links){
		    	xS[0] += 38;
		    }
		    else if(diffundieren && collidesWell() && oben){
		    	yS[0] += 33;
		    }
		    
		    //Snake neu zeichnen
		    for(int i = 0; i<length; i++){
		    playGround[xS[i]][yS[i]].setBackground(Color.cyan);
		    }
		    
		    //Kollisionstest
		    if(collidesSelf() || (collidesWell() && !diffundieren)){
		    gameover();
		    }
		    
		    //Kollisionstest mit Spielpunkten
		    for(int i = 0; i < 1; i++){
			    if(xS[i] == xP && yS[i] == yP){
				    neuerPunkt();
				    punkte += 1;
				    standUpdate();
				    xS[length] = xS[length -1];
				    yS[length] = yS[length -1];
				    length += 1;
				    if(max){
					    if(length == maxLength){
					    	success();
					    }
				    }
				    if(velChange){
				    	speed += 0.2;
				    	speedUpdate(speed);
				    }
				    
			    }
		    }
		    for(int i = 0; i<length; i++){
		    	if(xS[i] == xP && yS[i] == yP){
		    		neuerPunkt();
		    	}
		    }
		    
	    }
	    
    });
    
    timer.setInitialDelay(10);
    timer.start();
    speedUpdate(speed);
    setJMenuBar(null);
    
  }
  
  public void generatePlayground(Color c){
    
    for(int x = 0; x<40; x++){
      
      for(int y = 0; y<35; y++){
        
        playGround[x][y] = new JLabel("");
        playGround[x][y].setOpaque(true);
        playGround[x][y].setBackground(c);
        playGround[x][y].setBounds(x*10, y*10 + 30, 9, 9);
        cp.add(playGround[x][y]);
        if(x == 0 || x == 39 || y == 0 || y == 34){
          playGround[x][y].setBackground(Color.black);
        }
      }
      
    } 
  }
  
  public void repaintPlayground(Color c){
    for(int x = 0; x<40; x++){
      for(int y = 0; y<35; y++){
        if(x == 0 || x == 39 ){
          playGround[x][y].setBackground(Color.black);
        }
        else if(y == 0 || y == 34){
          playGround[x][y].setBackground(Color.black);
        }
        else{playGround[x][y].setBackground(c);}
      }
    }
  }
  
  //Erstellen des Spielstands
  public void spielstand(){
    
    punkte = 0;
    spielStand.setText(Integer.toString(punkte));
    spielStand.setBounds(160, 10, 80, 20);
    spielStand.setForeground(Color.WHITE);
    spielStand.setOpaque(true);;
    spielStand.setBackground(Color.gray);
    
  }
  
  public Color zufallsfarbe(){
    Color[] farben = {Color.magenta, Color.green, Color.blue, Color.red,
    Color.yellow, Color.cyan, Color.white};
    int q = (int)Math.floor(Math.random()*7);
    return farben[q];
  }
  
  //Update des Spielstands
  public void standUpdate(){
    spielStand.setText(Integer.toString(punkte));
  }
  
  //Erstellen eines neuen roten Punkts, Vergessen des alten
  public void neuerPunkt(){
    xP = (int)Math.floor(Math.random()*38) + 1;
    yP = (int)Math.floor(Math.random()*33) + 1;
    playGround[xP][yP].setBackground(Color.red);
  }
  
  public void success(){
    timer.stop();
    sTimer = new Timer(20, new ActionListener(){
    
    @Override
    public void actionPerformed(ActionEvent e) {
    	for(int x = 1; x<39; x++){
    		for(int y = 1; y<34; y++){
    			playGround[x][y].setBackground(zufallsfarbe());
    		}
    	}
    }
    
    });
    sTimer.start();
    spielStand.setText("You won!");
    exitWait = new Timer(5000, new ActionListener(){
    
    @Override
    public void actionPerformed(ActionEvent e) {
    	System.exit(0);
    }
    
    });
    exitWait.start();
  }
  
  public void gameover() {
    timer.stop();
    repaintPlayground(Color.darkGray);
    gameover.setOpaque(true);
    gameover.setBackground(Color.black);
    gameover.setForeground(Color.white);
    gameover.setVisible(true);
    exitWait2 = new Timer(1500, new ActionListener(){
    
    @Override
    public void actionPerformed(ActionEvent e) {
    	System.exit(0);
    }
    
    });
    exitWait2.start();
  }

  @Override
  public void keyPressed(KeyEvent e) {
    
    int key = e.getKeyCode();
    
    if(key == KeyEvent.VK_RIGHT && !links){
      rechts = true;
      oben = false;
      unten = false;
    }
    
    else if(key == KeyEvent.VK_LEFT && !rechts){
      links = true;
      oben = false;
      unten = false;
    }
    
    else if(key == KeyEvent.VK_UP && !unten){
      rechts = false;
      links = false;
      oben = true;
    }
    
    else if(key == KeyEvent.VK_DOWN && !oben){
      rechts = false;
      links = false;
      unten = true;
    }
    if(schnellPfeil){
    	speedUpdate(speed*1.5);
    }
  }

  @Override
  public void keyTyped(KeyEvent e) {
	  
  }

  @Override
  public void keyReleased(KeyEvent e) {
	 speedUpdate(speed);
  }
  
  public static void main(String[] args) {
	    new Snake();
	  }


}
