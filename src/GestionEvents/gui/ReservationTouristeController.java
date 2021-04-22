/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.gui;

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
import java.util.ResourceBundle;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.Pane;
import javax.swing.JOptionPane;

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
                if (JOptionPane.showConfirmDialog(null, "Voulez-vous faire une reservation pour cet evenement ?", "Reservation",
                   JOptionPane.YES_NO_OPTION) == JOptionPane.YES_OPTION) {
                    EventsCRUD eventsCRUD=new EventsCRUD();
                    double prix=eventsCRUD.recupererPrixEvent(monOffre.getEvenement_id());
                    prix=prix-((prix*monOffre.getPourcentage())/100);
                    Reservation reservation= new Reservation();
                    reservation.setTourist_id(1);
                    reservation.setEvenement_id(monOffre.getEvenement_id());
                    reservation.setOffre_id(monOffre.getId());
                    reservation.setDate_reservation(convertToDateViaInstant(LocalDate.now()));
                    reservation.setTarif(prix);
                    Date date=convertToDateViaInstant(LocalDate.now());
                    eventsCRUD.modifierNbrPlace(monOffre.getEvenement_id());
                    ressCRUD.ajouterReservation(reservation);
                    
                    
                  
                } else {
                 // no option
                }
            }    
        });
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
    
}
