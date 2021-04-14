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
import java.time.LocalDate;
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
public class AjouterResrvationController implements Initializable {

    @FXML
    private TextField textFieldUserID;
    @FXML
    private TextField textFieldEventID;
    @FXML
    private TextField textFieldOffreID;
    @FXML
    private TextField textFieldTarif;
    @FXML
    private DatePicker datePickerDateReservation;
    @FXML
    private Button boutonAjouter;
    @FXML
    private Button boutonRetour;
    private RessCRUD ressCRUD;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        ressCRUD= new RessCRUD();
        datePickerDateReservation.setValue(LocalDate.now());
    }    

    @FXML
    private void actionAjouter(ActionEvent event) {
        Reservation r=new Reservation();
        
        r.setEvenement_id(Integer.parseInt(textFieldEventID.getText()));
        r.setTourist_id(Integer.parseInt(textFieldUserID.getText()));
        r.setOffre_id(Integer.parseInt(textFieldOffreID.getText()));
        r.setDate_reservation(java.sql.Date.valueOf(datePickerDateReservation.getValue()));
        r.setTarif(Double.parseDouble(textFieldTarif.getText()));
        ressCRUD.ajouterReservation(r);
        textFieldUserID.setText("");
        textFieldEventID.setText("");
        textFieldOffreID.setText("");
        textFieldTarif.setText("");
        textFieldUserID.setText("");
        datePickerDateReservation.setValue(LocalDate.now());
    }

    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("ReservationFront.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }
    
}
