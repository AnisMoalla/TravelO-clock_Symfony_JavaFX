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
public class OfficeController implements Initializable {

    @FXML
    private Button boutonAdmin;
    @FXML
    private Button boutonUser;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        // TODO
    }    

    @FXML
    private void actionAdmin(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("GestionMvt.fxml"));
        Pane root = loader.load();
        boutonAdmin.getScene().setRoot(root);
    }

    @FXML
    private void actionUser(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("TourRes.fxml"));
        Pane root = loader.load();
        boutonUser.getScene().setRoot(root);
    }

    private void actionReserverUnePlace(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("TourRes.fxml"));
        Pane root = loader.load();
        boutonUser.getScene().setRoot(root);
    }
    
}
