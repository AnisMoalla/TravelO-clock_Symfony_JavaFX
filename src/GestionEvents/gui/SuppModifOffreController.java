/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.gui;

import GestionEvents.entites.Offre;
import GestionEvents.services.OffresCRUD;
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
public class SuppModifOffreController implements Initializable {

    @FXML
    private TextField textFieldNom;
    @FXML
    private TextField textFieldPourcentage;
    @FXML
    private Button boutonSupprimer;
    @FXML
    private Button boutonRetour;
    @FXML
    private DatePicker datePickerDateDebut;
    @FXML
    private DatePicker datePickerDateFin;
    @FXML
    private Button boutonModifier;
    EncapsulationOffre encapsulationOffre;
    OffresCRUD offresCRUD;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        encapsulationOffre=new EncapsulationOffre();
        offresCRUD=new OffresCRUD();
        textFieldPourcentage.setText(String.valueOf(encapsulationOffre.getPourcentage()));
        textFieldNom.setText(encapsulationOffre.getNom());
        datePickerDateDebut.setValue(encapsulationOffre.getDate_debut().toLocalDate());
        datePickerDateFin.setValue(encapsulationOffre.getDate_fin().toLocalDate());
    }    

    @FXML
    private void actionSupprimer(ActionEvent event) throws IOException {
        Offre offre = new Offre(encapsulationOffre.getNom(),encapsulationOffre.getDate_debut(),encapsulationOffre.getDate_fin(),encapsulationOffre.getPourcentage());
        offresCRUD.supprimerOffre(offre);
        FXMLLoader loader = new FXMLLoader(getClass().getResource("OffresBack.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("OffresBack.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

    @FXML
    private void actionModifier(ActionEvent event) throws IOException {
        Offre offre1 = new Offre(encapsulationOffre.getNom(),encapsulationOffre.getDate_debut(),encapsulationOffre.getDate_fin(),encapsulationOffre.getPourcentage());
        Offre offre2=new Offre();
        offre2.setNom(textFieldNom.getText());
        offre2.setDate_debut(java.sql.Date.valueOf(datePickerDateDebut.getValue()));
        offre2.setDate_fin(java.sql.Date.valueOf(datePickerDateFin.getValue()));
        offre2.setPourcentage(Integer.parseInt(textFieldPourcentage.getText()));
        offresCRUD.modifierOffre(offre1, offre2);
        FXMLLoader loader = new FXMLLoader(getClass().getResource("OffresBack.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }
    
}
