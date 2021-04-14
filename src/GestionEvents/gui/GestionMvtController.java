/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.gui;

import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.layout.Pane;

/**
 * FXML Controller class
 *
 * @author legion
 */
public class GestionMvtController implements Initializable {

    @FXML
    private Button buttonEvent;
    @FXML
    private Button buttonOffre;
    @FXML
    private Button buttonReservation;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        // TODO
    }    

    @FXML
    private void eventEvent(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("EventsBack.fxml"));
        Pane root = loader.load();
        buttonEvent.getScene().setRoot(root);
    }

    @FXML
    private void eventOffre(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("OffresBack.fxml"));
        Pane root = loader.load();
        buttonOffre.getScene().setRoot(root);
    }

    @FXML
    private void eventReservation(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("OffresBack.fxml"));
        Pane root = loader.load();
        buttonReservation.getScene().setRoot(root);
    }
    
}
