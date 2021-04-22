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
import javax.swing.JOptionPane;

/**
 * FXML Controller class
 *
 * @author legion
 */
public class AjouterOffreController implements Initializable {

    @FXML
    private TextField textFieldUserID;
    @FXML
    private TextField textFieldNom;
    @FXML
    private TextField textFieldPourcentage;
    @FXML
    private Button boutonValider;
    @FXML
    private Button boutonRetour;
    @FXML
    private DatePicker datePickerDateDebut;
    @FXML
    private DatePicker datePickerDateFin;
    private OffresCRUD offresCRUD;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        offresCRUD = new OffresCRUD();
        datePickerDateDebut.setValue(LocalDate.now());
        datePickerDateFin.setValue(LocalDate.now());
    }

    @FXML
    private void actionValider(ActionEvent event) {
        Offre o = new Offre();
        if (textFieldUserID.getText().isEmpty()) {
            JOptionPane.showMessageDialog(null, "Vous devez saisir un User ID d'abord !");
        } else {
//            if (textFieldEventID.getText().isEmpty()) {
              if (false) {
                JOptionPane.showMessageDialog(null, "Vous devez saisir un Event ID d'abord !");
            } else {
                if (textFieldNom.getText().isEmpty()) {
                    JOptionPane.showMessageDialog(null, "Vous devez saisir un Nom d'abord !");
                } else {
                    if (textFieldPourcentage.getText().isEmpty()) {
                        JOptionPane.showMessageDialog(null, "Vous devez saisir une Pourcentage d'abord !");
                    } else {
                        int resultat1 = datePickerDateDebut.getValue().compareTo(LocalDate.now());
                        if (resultat1 < 0) {
                            JOptionPane.showMessageDialog(null, "La Date de Debut doit etre correcte !");

                        } else {
                            int resultat2 = datePickerDateDebut.getValue().compareTo(datePickerDateFin.getValue());
                            if (resultat2 > 0) {
                                JOptionPane.showMessageDialog(null, "Date de Fin doit etre superieure à Date Debut !");
                            } else {
                                EncapsulationEvent encapsulationEvent=new EncapsulationEvent();
                                o.setUser_id(Integer.parseInt(textFieldUserID.getText()));
                                o.setEvenement_id(encapsulationEvent.getId());
                                o.setNom(textFieldNom.getText());
                                o.setDate_debut(java.sql.Date.valueOf(datePickerDateDebut.getValue()));
                                o.setDate_fin(java.sql.Date.valueOf(datePickerDateFin.getValue()));
                                o.setPourcentage(Integer.parseInt(textFieldPourcentage.getText()));
                                offresCRUD.ajouterOffre(o);
                                textFieldPourcentage.setText("");
                                textFieldNom.setText("");
                                textFieldUserID.setText("");
                                datePickerDateDebut.setValue(LocalDate.now());
                                 datePickerDateFin.setValue(LocalDate.now());
                            }
                        }
                    }
                }
            }

        }
    }

    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("EventsBack.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

}
