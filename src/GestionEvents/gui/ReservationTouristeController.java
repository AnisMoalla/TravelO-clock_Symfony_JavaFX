/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.gui;

import GestionEvents.entites.Evenement;
import GestionEvents.entites.Offre;
import GestionEvents.entites.Reservation;
import GestionEvents.services.EventsCRUD;
import GestionEvents.services.OffresCRUD;
import GestionEvents.services.RessCRUD;
import java.io.IOException;
import java.net.URL;
import java.sql.Date;
import java.time.LocalDate;
import java.time.ZoneId;
import java.util.List;
import java.util.Properties;
import java.util.ResourceBundle;
import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
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
import org.controlsfx.control.Rating;

/**
 * FXML Controller class
 *
 * @author legion
 */
public class ReservationTouristeController implements Initializable {

    @FXML
    private TableView<Offre> tableViewOffre;
    @FXML
    private TableColumn<Offre, String> colonneEvent;
    @FXML
    private TableColumn<Offre, String> colonneOffre;
    @FXML
    private TableColumn<Offre, Date> colonneDateDebut;
    @FXML
    private TableColumn<Offre, Date> colonneDateFin;
    @FXML
    private TableColumn<Offre, Integer> colonnePourcentage;
    @FXML
    private Button boutonRetour;
    private OffresCRUD offresCRUD;
    private List<Offre> offreList;
    @FXML
    private TableColumn<Offre, Integer> coloneIdEvent;
    @FXML
    private TableColumn<Offre, Integer> coloneIdOffre;
    private RessCRUD ressCRUD;
    @FXML
    private Button boutonVoter;
    @FXML
    private Rating rating;
    int ratingNumber = 0;


    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        offresCRUD = new OffresCRUD();
        ressCRUD = new RessCRUD();
        offreList = offresCRUD.afficherOffre();
        
        //Dans le propertyValueFactory contien le nom
                                                                                   // de l'attribut dans l'entité
        colonneEvent.setCellValueFactory(new PropertyValueFactory("eventname"));
        colonneOffre.setCellValueFactory(new PropertyValueFactory("nom"));
        colonneDateDebut.setCellValueFactory(new PropertyValueFactory("date_debut"));
        colonneDateFin.setCellValueFactory(new PropertyValueFactory("date_fin"));
        colonnePourcentage.setCellValueFactory(new PropertyValueFactory("pourcentage"));
        coloneIdEvent.setCellValueFactory(new PropertyValueFactory("evenement_id"));
        coloneIdOffre.setCellValueFactory(new PropertyValueFactory("id"));
      
        for(int i=0;i<offreList.size();i++)
        {
           tableViewOffre.getItems().add(offreList.get(i));
        }
        
        
        
        
        
        tableViewOffre.setOnMouseClicked((javafx.scene.input.MouseEvent event) ->{
            if (event.getClickCount()==2){
                Offre monOffre=tableViewOffre.getSelectionModel().getSelectedItem();
                int indexSelectionne=tableViewOffre.getSelectionModel().getSelectedIndex();
                EncapsulationOffre eo=new EncapsulationOffre(monOffre.getId(), monOffre.getEvenement_id() ,monOffre.getNom(), monOffre.getDate_debut(), monOffre.getDate_fin(), monOffre.getPourcentage());
                FXMLLoader loader = new FXMLLoader(getClass().getResource("DetailsReservationEvent.fxml"));
        Pane root;
                try {
                    root = loader.load();
                    boutonRetour.getScene().setRoot(root);
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
                
                
//                if (JOptionPane.showConfirmDialog(null, "Voulez-vous faire une reservation pour cet evenement ?", "Reservation",
//                   JOptionPane.YES_NO_OPTION) == JOptionPane.YES_OPTION) {
//                    sendMail("anis.moalla@esprit.tn","Reservation Ajouté");
//                    EventsCRUD eventsCRUD=new EventsCRUD();
//                    double prix=eventsCRUD.recupererPrixEvent(monOffre.getEvenement_id());
//                    prix=prix-((prix*monOffre.getPourcentage())/100);
//                    Reservation reservation= new Reservation();
//                    reservation.setTourist_id(1);
//                    reservation.setEvenement_id(monOffre.getEvenement_id());
//                    reservation.setOffre_id(monOffre.getId());
//                    reservation.setDate_reservation(convertToDateViaInstant(LocalDate.now()));
//                    reservation.setTarif(prix);
//                    Date date=convertToDateViaInstant(LocalDate.now());
//                    eventsCRUD.modifierNbrPlace(monOffre.getEvenement_id());
//                    ressCRUD.ajouterReservation(reservation);
//                    
//                    
//                  
//                } else {
//                 // no option
//                }
            }    
        });
        
        rating.ratingProperty().addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                ratingNumber = newValue.intValue();
            }
        });
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
    
    public Date convertToDateViaInstant(LocalDate dateToConvert) {
    return java.sql.Date.valueOf(dateToConvert);
       }


    
    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("TourRes.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

    @FXML
    private void actionVoter(ActionEvent event) {
        Offre monOffre=tableViewOffre.getSelectionModel().getSelectedItem();
        EventsCRUD eventsCRUD=new EventsCRUD();
        Evenement tempEvent2=eventsCRUD.getEventById(monOffre.getEvenement_id());
        Evenement tempEvent1=tempEvent2;
        
        tempEvent2.setVote(tempEvent2.getVote()+1);
        tempEvent2.setRate((((tempEvent2.getRate()/tempEvent1.getVote())*tempEvent2.getVote())+ratingNumber)/tempEvent2.getVote());
        double d = (double) Math.round(tempEvent2.getRate() * 100) / 100;
        tempEvent2.setRate(d);
        eventsCRUD.voter(tempEvent2);
    }
    
}
