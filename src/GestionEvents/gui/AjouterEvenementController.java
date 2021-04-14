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
import java.sql.Date;
import java.text.SimpleDateFormat;
import java.time.LocalDate;
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
import javax.swing.JOptionPane;

/**
 * FXML Controller class
 *
 * @author legion
 */
public class AjouterEvenementController implements Initializable {

    @FXML
    private TextField textFieldNom;
    @FXML
    private DatePicker datePickerDateDebut;
    @FXML
    private TextField textFieldUserID;
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
    private TextField textFieldImage;
    @FXML
    private Button boutonRetour;
    @FXML
    private Button boutonValider;
    private EventsCRUD eventsCRUD;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        eventsCRUD = new EventsCRUD();
        datePickerDateDebut.setValue(LocalDate.now());
        datePickerDateFin.setValue(LocalDate.now());
    }

    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("EventsBack.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

    @FXML
    private void actionValider(ActionEvent event) {
        Evenement e = new Evenement();
        if (textFieldUserID.getText().isEmpty()) {
            JOptionPane.showMessageDialog(null, "Vous devez saisir un User ID d'abord !");
        } else {
            if (textFieldNom.getText().isEmpty()) {
                JOptionPane.showMessageDialog(null, "Vous devez saisir un Nom d'abord !");
            } else {
                int resultat1 = datePickerDateDebut.getValue().compareTo(LocalDate.now());
                if (resultat1 <= 0) {
                    JOptionPane.showMessageDialog(null, "La Date de Debut doit etre correcte !");
                } else {
                    int resultat2 = datePickerDateFin.getValue().compareTo(datePickerDateDebut.getValue());
                    if (resultat2 > 0) {
                        JOptionPane.showMessageDialog(null, "Date de Fin doit etre superieure à Date Debut !");
                    } else {
                        if (textFieldPays.getText().isEmpty()) {
                            JOptionPane.showMessageDialog(null, "Vous devez saisir un Pays d'abord !");
                        } else {
                            if (textFieldVille.getText().isEmpty()) {
                                JOptionPane.showMessageDialog(null, "Vous devez saisir un Ville d'abord !");
                            } else {
                                if (textFieldImage.getText().isEmpty()) {
                                    JOptionPane.showMessageDialog(null, "Vous devez saisir un Image d'abord !");
                                } else {
                                    if (textFieldNbrPlaces.getText().isEmpty()) {
                                        JOptionPane.showMessageDialog(null, "Vous devez saisir le nombre des Places d'abord !");
                                    } else {
                                        e.setUser_id(Integer.parseInt(textFieldUserID.getText()));
                                        e.setNom(textFieldNom.getText());
                                        e.setDate_debut(java.sql.Date.valueOf(datePickerDateDebut.getValue()));
                                        e.setDate_fin(java.sql.Date.valueOf(datePickerDateFin.getValue()));
                                        e.setDescription(textAreaDescription.getText());
                                        e.setPays(textFieldPays.getText());
                                        e.setVille(textFieldVille.getText());
                                        e.setNbr_places(Integer.parseInt(textFieldNbrPlaces.getText()));
                                        e.setImage(textFieldImage.getText());
                                        e.setPrix(Double.parseDouble(textFieldPrix.getText()));
                                        eventsCRUD.ajouterEvent(e);
                                        textAreaDescription.setText("");
                                        textFieldImage.setText("");
                                        textFieldNbrPlaces.setText("");
                                        textFieldNom.setText("");
                                        textFieldPays.setText("");
                                        textFieldPrix.setText("");
                                        textFieldUserID.setText("");
                                        textFieldVille.setText("");
                                        datePickerDateDebut.setValue(LocalDate.now());
                                        datePickerDateFin.setValue(LocalDate.now());
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    }

}
