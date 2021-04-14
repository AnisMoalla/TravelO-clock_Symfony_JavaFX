/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.gui;

import GestionEvents.entites.Reservation;
import GestionEvents.services.RessCRUD;
import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.DatePicker;
import javafx.scene.control.TextField;
import javafx.scene.layout.Pane;

/**
 * FXML Controller class
 *
 * @author legion
 */
public class SuppModifReservationController implements Initializable {

    @FXML
    private TextField textFieldEventID;
    @FXML
    private TextField textFieldTarif;
    @FXML
    private DatePicker datePickerDateReservation;
    @FXML
    private Button boutonModifier;
    @FXML
    private Button boutonRetour;
    @FXML
    private Button boutonSupprimer;
    EncapsulationResrvation encapsulationreservation;
    RessCRUD ressCRUD;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        encapsulationreservation=new EncapsulationResrvation();
        ressCRUD=new RessCRUD();
        textFieldTarif.setText(String.valueOf(encapsulationreservation.getTarif()));
        //textFieldEventID.setText(encapsulationreservation.getUsername());
        datePickerDateReservation.setValue(encapsulationreservation.getDate_reservation().toLocalDate());
    }    

    @FXML
    private void actionModifier(ActionEvent event) throws IOException {
        Reservation reservation1 = new Reservation(encapsulationreservation.getEvenement_id(),encapsulationreservation.getDate_reservation(),encapsulationreservation.getTarif());
        Reservation reservation2=new Reservation();
        reservation2.setEvenement_id(Integer.parseInt(textFieldEventID.getText()));
        reservation2.setDate_reservation(java.sql.Date.valueOf(datePickerDateReservation.getValue()));
        reservation2.setTarif(Double.parseDouble(textFieldTarif.getText()));
        ressCRUD.modifierReservation(reservation1, reservation2);
        FXMLLoader loader = new FXMLLoader(getClass().getResource("ReservationFront.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("ReservationFront.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

    @FXML
    private void actionSupprimer(ActionEvent event) throws IOException {
        Reservation reservation = new Reservation(Integer.parseInt(textFieldEventID.getText()),encapsulationreservation.getDate_reservation(),encapsulationreservation.getTarif());
        ressCRUD.supprimerReservation(reservation);
        FXMLLoader loader = new FXMLLoader(getClass().getResource("ReservationFront.fxml"));
        Pane root = loader.load();
        boutonSupprimer.getScene().setRoot(root);
    }
    
}
