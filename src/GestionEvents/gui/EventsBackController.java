/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.gui;

import GestionEvents.entites.Evenement;
import GestionEvents.services.EventsCRUD;
import GestionEvents.tools.MyConnection;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.URL;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Date;
import java.util.List;
import java.util.Properties;
import java.util.ResourceBundle;
import java.util.logging.Level;
import java.util.logging.Logger;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.collections.transformation.FilteredList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextArea;
import javafx.scene.control.TextField;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.Pane;
import javax.mail.Authenticator;
import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.PasswordAuthentication;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;
import javax.swing.JOptionPane;

/**
 * FXML Controller class
 *
 * @author legion
 */
public class EventsBackController implements Initializable {

    @FXML
    private TableView<Evenement> tableViewEvent;
    @FXML
    private TableColumn<Evenement, String> colonneUsername;
    @FXML
    private TableColumn<Evenement, String> colonneNom;
    @FXML
    private TableColumn<Evenement, Date> colonneDateDebut;
    @FXML
    private TableColumn<Evenement, Date> colonneDateFin;
    @FXML
    private TableColumn<Evenement, String> colonneDescription;
    @FXML
    private TableColumn<Evenement, String> colonnePays;
    @FXML
    private TableColumn<Evenement, String> colonneVille;
    @FXML
    private TableColumn<Evenement, Double> colonnePrix;
    @FXML
    private TableColumn<Evenement, Integer> colonnePlace;
    @FXML
    private TableColumn<Evenement, Double> colonneNote;
    @FXML
    private Button boutonAjouterEvent;
    @FXML
    private Button boutonRetour;
    private EventsCRUD eventsCRUD;
    private List<Evenement> evenementList;
    ObservableList<Evenement> obl = FXCollections.observableArrayList();
    @FXML
    private TextField in_search;
    @FXML
    private Button btn_csv;
    @FXML
    private Button btn_mail;
    @FXML
    private TextArea mailContent;
    @FXML
    private TableColumn<Evenement, Integer> idEvent;
    @FXML
    private Button buttonStat;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        eventsCRUD = new EventsCRUD();
        evenementList = eventsCRUD.afficherEvent();
        search_event();

//        colonneUsername.setCellValueFactory(new PropertyValueFactory("username"));
//        colonneDateDebut.setCellValueFactory(new PropertyValueFactory("date_debut"));
//        colonneDateFin.setCellValueFactory(new PropertyValueFactory("date_fin"));
//        colonneDescription.setCellValueFactory(new PropertyValueFactory("description"));
//        colonneNom.setCellValueFactory(new PropertyValueFactory("nom"));
//        colonneNote.setCellValueFactory(new PropertyValueFactory("rate"));
//        colonnePays.setCellValueFactory(new PropertyValueFactory("pays"));
//        colonnePlace.setCellValueFactory(new PropertyValueFactory("nbr_places"));
//        colonnePrix.setCellValueFactory(new PropertyValueFactory("prix"));
//        colonneVille.setCellValueFactory(new PropertyValueFactory("ville"));
//        
//        for(int i=0;i<evenementList.size();i++)
//        {
//           tableViewEvent.getItems().add(evenementList.get(i));
//        }
        tableViewEvent.setOnMouseClicked((javafx.scene.input.MouseEvent event) -> {
            if (event.getClickCount() == 2) {

                Evenement monEvent = tableViewEvent.getSelectionModel().getSelectedItem();
                EncapsulationEvent encapsulationEvent = new EncapsulationEvent(monEvent.getId(),monEvent.getNom(), monEvent.getDate_debut(), monEvent.getDate_fin(),
                         monEvent.getDescription(), monEvent.getPays(), monEvent.getVille(), monEvent.getPrix(), monEvent.getNbr_places());
                FXMLLoader loader = new FXMLLoader(getClass().getResource("SuppModifEvenement.fxml"));
                Pane root = null;
                try {
                    root = loader.load();
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
                boutonAjouterEvent.getScene().setRoot(root);
            }
        });

    }

    @FXML
    private void eventAjouter(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("AjouterEvenement.fxml"));
        Pane root = loader.load();
        boutonAjouterEvent.getScene().setRoot(root);
    }

    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("GestionMvt.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

    private void search_event() {
        colonneUsername.setCellValueFactory(new PropertyValueFactory("username"));
        colonneDateDebut.setCellValueFactory(new PropertyValueFactory("date_debut"));
        colonneDateFin.setCellValueFactory(new PropertyValueFactory("date_fin"));
        colonneDescription.setCellValueFactory(new PropertyValueFactory("description"));
        colonneNom.setCellValueFactory(new PropertyValueFactory("nom"));
        colonneNote.setCellValueFactory(new PropertyValueFactory("rate"));
        colonnePays.setCellValueFactory(new PropertyValueFactory("pays"));
        colonnePlace.setCellValueFactory(new PropertyValueFactory("nbr_places"));
        colonnePrix.setCellValueFactory(new PropertyValueFactory("prix"));
        colonneVille.setCellValueFactory(new PropertyValueFactory("ville"));
        idEvent.setCellValueFactory(new PropertyValueFactory("id"));
        try {

            String requete = "SELECT evenement.id, user.prenom,evenement.nom,evenement.date_debut, evenement.date_fin, evenement.description, evenement.pays, evenement.ville, evenement.prix, evenement.nbr_places, evenement.rate"
                    + " FROM user,evenement"
                    + " where user.id=evenement.user_id";
            Statement st = MyConnection.getInstance().getCnx().createStatement();
            ResultSet rs = st.executeQuery(requete);
            while (rs.next()) {
                obl.add(new Evenement(rs.getInt(1),rs.getString(2), rs.getString(3), rs.getDate(4), rs.getDate(5), rs.getString(6), rs.getString(7), rs.getString(8), rs.getDouble(9), rs.getInt(10), rs.getDouble(11)));

            }

        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
        tableViewEvent.setItems(obl);
        FilteredList<Evenement> filteredData = new FilteredList<>(obl, b -> true);
        in_search.textProperty().addListener((observable, oldValue, newValue) -> {
            filteredData.setPredicate(evenement -> {
                if (newValue == null || newValue.isEmpty()) {
                    return true;
                }
                String lowerCaseFilter = newValue.toLowerCase();

                if (evenement.getUsername().toLowerCase().indexOf(lowerCaseFilter) != -1) {
                    return true; // Filter matches username
                } else if (evenement.getPays().toLowerCase().indexOf(lowerCaseFilter) != -1) {
                    return true; // Filter matches password
                } else if (evenement.getVille().toLowerCase().indexOf(lowerCaseFilter) != -1) {
                    return true; // Filter matches password
                } else if (evenement.getDescription().toLowerCase().indexOf(lowerCaseFilter) != -1) {
                    return true; // Filter matches password
                } else if (String.valueOf(evenement.getId()).indexOf(lowerCaseFilter) != -1) {
                    return true; // Filter matches password
                } else if (String.valueOf(evenement.getPrix()).indexOf(lowerCaseFilter) != -1) {
                    return true; // Filter matches password
                } else if (String.valueOf(evenement.getRate()).indexOf(lowerCaseFilter) != -1) {
                    return true; // Filter matches password
                } else if (String.valueOf(evenement.getNom()).indexOf(lowerCaseFilter) != -1) {
                    return true;// Filter matches email
                } else if (String.valueOf(evenement.getNbr_places()).indexOf(lowerCaseFilter) != -1) {
                    return true;// Filter matches email
                }else if (String.valueOf(evenement.getDate_debut()).indexOf(lowerCaseFilter) != -1) {
                    return true;// Filter matches email
                }else if (String.valueOf(evenement.getDate_fin()).indexOf(lowerCaseFilter) != -1) {
                    return true;// Filter matches email
                }else{
                    return false; // Does not match.
                }
            });
        });

        tableViewEvent.setItems(filteredData);
    }

    @FXML
    private void CSV(ActionEvent event) {
        saveheader("dataAnis.csv");
        for (Evenement e : obl) {
            saveRecord(e, "dataAnis.csv");
        }
    }

    private void saveheader(String filePath) {
        try {
            FileWriter fw = new FileWriter(filePath, false);
            BufferedWriter bw = new BufferedWriter(fw);
            PrintWriter pw = new PrintWriter(bw);

            pw.println("DateDebut,Pays,Ville");
            pw.flush();
            pw.close();

           
        } catch (Exception E) {
            System.out.println("Record not saved");
        }
    }

    public static void saveRecord(Evenement evenement, String filepath) {
        try {
            FileWriter fw = new FileWriter(filepath, true);
            BufferedWriter bw = new BufferedWriter(fw);
            PrintWriter pw = new PrintWriter(bw);

            pw.println(evenement.getDate_debut() + "," + evenement.getPays() + "," + evenement.getVille());
            pw.flush();
            pw.close();

            } catch (Exception E) {
            System.out.println("Record not saved");
        }
    }

    @FXML
    private void sendMail(ActionEvent event) {
        sendMail("anis.moalla@esprit.tn",mailContent.getText());
    }
    
    private void sendMail(String recepient,String messageToSend) {
        System.out.println("preparing to send mail");
        Properties properties = new Properties();
        properties.put("mail.smtp.auth", true);
        properties.put("mail.smtp.starttls.enable", true);
        properties.put("mail.smtp.host", "smtp.gmail.com");
        properties.put("mail.smtp.port", "587");

        String myAccountEmail = "projetpidev992@gmail.com";
        String password = "ozxcgepevofquhfb";

        Session session = Session.getInstance(properties, new Authenticator() {
            @Override
            protected PasswordAuthentication getPasswordAuthentication() {
                return new PasswordAuthentication(myAccountEmail, password);
            }
        }
        );
        Message message =  prepareMessage(session,myAccountEmail,recepient,messageToSend);
        try {
            Transport.send(message);
        } catch (MessagingException ex) {
            System.out.println(ex.getMessage());
        }
        System.out.println("message sent succefully!!!");
        
     }
    private static Message prepareMessage(Session session, String myAccountEmail, String recepient,String messageToSend) {
        try {
            Message message= new MimeMessage(session);
            message.setFrom(new InternetAddress(myAccountEmail));
            message.setRecipient(Message.RecipientType.TO, new InternetAddress(recepient));
            message.setSubject("Mail Events");
            message.setText(messageToSend);
            return message;
        } catch (Exception e) {
            System.out.println(e.getMessage());
        }
        return null;
    }

    @FXML
    private void actionStat(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("StatEvent.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

}
