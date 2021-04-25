/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.gui;

import GestionEvents.entites.Evenement;
import GestionEvents.services.EventsCRUD;
import java.io.File;
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
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import javafx.stage.FileChooser;
import javafx.util.Duration;
import javax.swing.JOptionPane;
import tray.animations.AnimationType;
import tray.notification.NotificationType;
import tray.notification.TrayNotification;

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
    private Button textFieldImage;
    @FXML
    private Button boutonRetour;
    @FXML
    private Button boutonValider;
    private EventsCRUD eventsCRUD;
    @FXML
    private ImageView imageVue;
    final FileChooser fileChooser = new FileChooser();
    String imagepath = "null";

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
            textFieldUserID.setStyle("-fx-border-color: red; -fx-borfrt-width: 2px;");
            new animatefx.animation.Shake(textFieldUserID).play();
            TrayNotification tray = new TrayNotification();
            AnimationType type = AnimationType.SLIDE;
            tray.setAnimationType(type);
            tray.setTitle("Ajouter Evenement");
            tray.setMessage("hello there");
            tray.setNotificationType(NotificationType.INFORMATION);//
            tray.showAndDismiss(Duration.millis(3000));
            //JOptionPane.showMessageDialog(null, "Vous devez saisir un User ID d'abord !");
        } else {
            if (textFieldNom.getText().isEmpty()) {
                textFieldNom.setStyle("-fx-border-color: red; -fx-borfrt-width: 2px;");
                new animatefx.animation.Shake(textFieldNom).play();
                TrayNotification tray = new TrayNotification();
                AnimationType type = AnimationType.SLIDE;
                tray.setAnimationType(type);
                tray.setTitle("Ajouter Evenement");
                tray.setMessage("Vous devez saisir un Nom d'abord !");
                tray.setNotificationType(NotificationType.INFORMATION);//
                tray.showAndDismiss(Duration.millis(3000));

            } else {
                int resultat1 = datePickerDateDebut.getValue().compareTo(LocalDate.now());
                if (resultat1 <= 0) {
                    datePickerDateDebut.setStyle("-fx-border-color: red; -fx-borfrt-width: 2px;");
                    new animatefx.animation.Shake(datePickerDateDebut).play();
                    TrayNotification tray = new TrayNotification();
                    AnimationType type = AnimationType.SLIDE;
                    tray.setAnimationType(type);
                    tray.setTitle("Ajouter Evenement");
                    tray.setMessage("La Date de Debut doit etre correcte !");
                    tray.setNotificationType(NotificationType.INFORMATION);//
                    tray.showAndDismiss(Duration.millis(3000));

                } else {
                    int resultat2 = datePickerDateFin.getValue().compareTo(datePickerDateDebut.getValue());
                    if (resultat2 < 0) {
                        datePickerDateFin.setStyle("-fx-border-color: red; -fx-borfrt-width: 2px;");
                        new animatefx.animation.Shake(datePickerDateFin).play();
                        TrayNotification tray = new TrayNotification();
                        AnimationType type = AnimationType.SLIDE;
                        tray.setAnimationType(type);
                        tray.setTitle("Ajouter Evenement");
                        tray.setMessage("Date de Fin doit etre superieure à Date Debut !");
                        tray.setNotificationType(NotificationType.INFORMATION);//
                        tray.showAndDismiss(Duration.millis(3000));

                    } else {
                        if (textAreaDescription.getText().isEmpty()) {
                            textAreaDescription.setStyle("-fx-border-color: red; -fx-borfrt-width: 2px;");
                            new animatefx.animation.Shake(textAreaDescription).play();
                            TrayNotification tray = new TrayNotification();
                            AnimationType type = AnimationType.SLIDE;
                            tray.setAnimationType(type);
                            tray.setTitle("Ajouter Evenement");
                            tray.setMessage("Vous devez saisir une description d'abord !");
                            tray.setNotificationType(NotificationType.INFORMATION);//
                            tray.showAndDismiss(Duration.millis(3000));
                        } else {
                            if (textFieldPays.getText().isEmpty()) {
                                textFieldPays.setStyle("-fx-border-color: red; -fx-borfrt-width: 2px;");
                                new animatefx.animation.Shake(textFieldPays).play();
                                TrayNotification tray = new TrayNotification();
                                AnimationType type = AnimationType.SLIDE;
                                tray.setAnimationType(type);
                                tray.setTitle("Ajouter Evenement");
                                tray.setMessage("Vous devez saisir un Pays d'abord !");
                                tray.setNotificationType(NotificationType.INFORMATION);//
                                tray.showAndDismiss(Duration.millis(3000));

                            } else {
                                if (textFieldVille.getText().isEmpty()) {
                                    textFieldVille.setStyle("-fx-border-color: red; -fx-borfrt-width: 2px;");
                                    new animatefx.animation.Shake(textFieldVille).play();
                                    TrayNotification tray = new TrayNotification();
                                    AnimationType type = AnimationType.SLIDE;
                                    tray.setAnimationType(type);
                                    tray.setTitle("Ajouter Evenement");
                                    tray.setMessage("Vous devez saisir un Ville d'abord !");
                                    tray.setNotificationType(NotificationType.INFORMATION);//
                                    tray.showAndDismiss(Duration.millis(3000));

                                } else {
                                    if (textFieldNbrPlaces.getText().isEmpty()) {
                                        textFieldNbrPlaces.setStyle("-fx-border-color: red; -fx-borfrt-width: 2px;");
                                        new animatefx.animation.Shake(textFieldNbrPlaces).play();
                                        TrayNotification tray = new TrayNotification();
                                        AnimationType type = AnimationType.SLIDE;
                                        tray.setAnimationType(type);
                                        tray.setTitle("Ajouter Evenement");
                                        tray.setMessage("Vous devez saisir le nombre des Places d'abord !");
                                        tray.setNotificationType(NotificationType.INFORMATION);//
                                        tray.showAndDismiss(Duration.millis(3000));

                                    } else {
                                        if (textFieldPrix.getText().isEmpty()) {
                                            textFieldPrix.setStyle("-fx-border-color: red; -fx-borfrt-width: 2px;");
                                            new animatefx.animation.Shake(textFieldPrix).play();
                                            TrayNotification tray = new TrayNotification();
                                            AnimationType type = AnimationType.SLIDE;
                                            tray.setAnimationType(type);
                                            tray.setTitle("Ajouter Evenement");
                                            tray.setMessage("Vous devez saisir le prix d'abord !");
                                            tray.setNotificationType(NotificationType.INFORMATION);//
                                            tray.showAndDismiss(Duration.millis(3000));

                                        } else {
                                            e.setUser_id(Integer.parseInt(textFieldUserID.getText()));
                                            e.setNom(textFieldNom.getText());
                                            e.setDate_debut(java.sql.Date.valueOf(datePickerDateDebut.getValue()));
                                            e.setDate_fin(java.sql.Date.valueOf(datePickerDateFin.getValue()));
                                            e.setDescription(textAreaDescription.getText());
                                            e.setPays(textFieldPays.getText());
                                            e.setVille(textFieldVille.getText());
                                            e.setNbr_places(Integer.parseInt(textFieldNbrPlaces.getText()));
                                            e.setImage(imagepath.substring(104));
                                            e.setPrix(Double.parseDouble(textFieldPrix.getText()));
                                            eventsCRUD.ajouterEvent(e);
                                            TrayNotification tray = new TrayNotification();
                                            AnimationType type = AnimationType.SLIDE;
                                            tray.setAnimationType(type);
                                            tray.setTitle("Ajouter Evenement");
                                            tray.setMessage("Evenement Ajouté");
                                            tray.setNotificationType(NotificationType.SUCCESS);//
                                            tray.showAndDismiss(Duration.millis(3000));
                                            textAreaDescription.setText("");
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

    @FXML
    private void eventImage(ActionEvent event) {
        File file = fileChooser.showOpenDialog(null);
        if (file != null) {
            imagepath = file.toURI().toString();
            Image image = new Image(imagepath);
            imageVue.setImage(image);
        }
    }

}
