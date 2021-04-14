/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.gui;

import GestionEvents.entites.Evenement;
import GestionEvents.services.EventsCRUD;
import java.io.IOException;
import java.net.URL;
import java.time.ZoneId;
import java.util.ResourceBundle;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.DatePicker;
import javafx.scene.control.TextArea;
import javafx.scene.control.TextField;
import javafx.scene.layout.Pane;

/**
 * FXML Controller class
 *
 * @author legion
 */
public class SuppModifEvenementController implements Initializable {

    @FXML
    private TextField textFieldNom;
    @FXML
    private DatePicker datePickerDateDebut;
    @FXML
    private DatePicker datePickerDateFin;
    @FXML
    private TextArea textAreaDescription;
    @FXML
    private TextField textFieldPays;
    @FXML
    private TextField textFieldNbrPlaces;
    @FXML
    private TextField textFieldVille;
    @FXML
    private TextField textFieldPrix;
    @FXML
    private Button boutonRetour;
    @FXML
    private Button boutonModifier;
    @FXML
    private Button boutonSupprimer;
    EncapsulationEvent encapsulationEvent;
    EventsCRUD eventsCRUD;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        encapsulationEvent=new EncapsulationEvent();
        eventsCRUD=new EventsCRUD();
        textFieldNbrPlaces.setText(String.valueOf(encapsulationEvent.getNbr_places()));
        textFieldNom.setText(encapsulationEvent.getNom());
        textFieldPays.setText(encapsulationEvent.getPays());
        textFieldVille.setText(encapsulationEvent.getVille());
        textFieldPrix.setText(String.valueOf(encapsulationEvent.getPrix()));
        textAreaDescription.setText(encapsulationEvent.getDescription());
        datePickerDateDebut.setValue(encapsulationEvent.getDate_debut().toLocalDate());
        datePickerDateFin.setValue(encapsulationEvent.getDate_fin().toLocalDate());
        
    }    

    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("EventsBack.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

    @FXML
    private void actionModifier(ActionEvent event) throws IOException {
        Evenement evenement1 = new Evenement(encapsulationEvent.getNom(),encapsulationEvent.getDate_debut(),encapsulationEvent.getDate_fin(),encapsulationEvent.getDescription(),encapsulationEvent.getPays(),encapsulationEvent.getVille(),encapsulationEvent.getPrix(),encapsulationEvent.getNbr_places());
        Evenement evenement2=new Evenement();
        evenement2.setNom(textFieldNom.getText());
        evenement2.setDate_debut(java.sql.Date.valueOf(datePickerDateDebut.getValue()));
        evenement2.setDate_fin(java.sql.Date.valueOf(datePickerDateFin.getValue()));
        evenement2.setDescription(textAreaDescription.getText());
        evenement2.setPays(textFieldPays.getText());
        evenement2.setVille(textFieldVille.getText());
        evenement2.setNbr_places(Integer.parseInt(textFieldNbrPlaces.getText()));
        evenement2.setPrix(Double.parseDouble(textFieldPrix.getText()));
        eventsCRUD.modifierEvent(evenement1, evenement2);
        FXMLLoader loader = new FXMLLoader(getClass().getResource("EventsBack.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

    @FXML
    private void actionSupprimer(ActionEvent event) throws IOException { 
        Evenement evenement = new Evenement(encapsulationEvent.getNom(),encapsulationEvent.getDate_debut(),encapsulationEvent.getDate_fin(),encapsulationEvent.getDescription(),encapsulationEvent.getPays(),encapsulationEvent.getVille(),encapsulationEvent.getPrix(),encapsulationEvent.getNbr_places());
        eventsCRUD.supprimerEvent(evenement);
        FXMLLoader loader = new FXMLLoader(getClass().getResource("EventsBack.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }
    
}
