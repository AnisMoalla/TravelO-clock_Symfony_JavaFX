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
public class TourResController implements Initializable {

    @FXML
    private Button buttonAjRes;
    @FXML
    private Button buttonMesRes;
    @FXML
    private Button buttonRetour;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        // TODO
    }    

    @FXML
    private void eventAjRes(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("ReservationTouriste.fxml"));
        Pane root = loader.load();
        buttonAjRes.getScene().setRoot(root);
    }

    @FXML
    private void eventMesRes(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("ReservationFront.fxml"));
        Pane root = loader.load();
        buttonMesRes.getScene().setRoot(root);
    }

    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("Office.fxml"));
        Pane root = loader.load();
        buttonMesRes.getScene().setRoot(root);
    }
    
}
