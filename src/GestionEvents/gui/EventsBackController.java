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
import java.util.Date;
import java.util.List;
import java.util.ResourceBundle;
import java.util.logging.Level;
import java.util.logging.Logger;
import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.Pane;
import javax.swing.JOptionPane;

/**
 * FXML Controller class
 *
 * @author legion
 */
public class EventsBackController implements Initializable {

    @FXML
    private TableView<Evenement> tableViewEvent;
    @FXML
    private TableColumn<Evenement, String> colonneUsername;
    @FXML
    private TableColumn<Evenement, String> colonneNom;
    @FXML
    private TableColumn<Evenement, Date> colonneDateDebut;
    @FXML
    private TableColumn<Evenement, Date> colonneDateFin;
    @FXML
    private TableColumn<Evenement, String> colonneDescription;
    @FXML
    private TableColumn<Evenement, String> colonnePays;
    @FXML
    private TableColumn<Evenement, String> colonneVille;
    @FXML
    private TableColumn<Evenement, Double> colonnePrix;
    @FXML
    private TableColumn<Evenement, Integer> colonnePlace;
    @FXML
    private TableColumn<Evenement, Double> colonneNote;
    @FXML
    private Button boutonAjouterEvent;
    @FXML
    private Button boutonRetour;
    private EventsCRUD eventsCRUD;
    private List<Evenement> evenementList;

    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        eventsCRUD= new EventsCRUD();
        evenementList=eventsCRUD.afficherEvent();
        
        colonneUsername.setCellValueFactory(new PropertyValueFactory("username"));
        colonneDateDebut.setCellValueFactory(new PropertyValueFactory("date_debut"));
        colonneDateFin.setCellValueFactory(new PropertyValueFactory("date_fin"));
        colonneDescription.setCellValueFactory(new PropertyValueFactory("description"));
        colonneNom.setCellValueFactory(new PropertyValueFactory("nom"));
        colonneNote.setCellValueFactory(new PropertyValueFactory("rate"));
        colonnePays.setCellValueFactory(new PropertyValueFactory("pays"));
        colonnePlace.setCellValueFactory(new PropertyValueFactory("nbr_places"));
        colonnePrix.setCellValueFactory(new PropertyValueFactory("prix"));
        colonneVille.setCellValueFactory(new PropertyValueFactory("ville"));
        
        for(int i=0;i<evenementList.size();i++)
        {
           tableViewEvent.getItems().add(evenementList.get(i));
        }
        
        
        tableViewEvent.setOnMouseClicked((javafx.scene.input.MouseEvent event) ->{
            if (event.getClickCount()==2){
                
                Evenement monEvent=tableViewEvent.getSelectionModel().getSelectedItem();
                EncapsulationEvent encapsulationEvent=new EncapsulationEvent(monEvent.getNom(),monEvent.getDate_debut(),monEvent.getDate_fin()
                ,monEvent.getDescription(),monEvent.getPays(),monEvent.getVille(),monEvent.getPrix(),monEvent.getNbr_places());
                FXMLLoader loader = new FXMLLoader(getClass().getResource("SuppModifEvenement.fxml"));
                Pane root = null;
                try {
                    root = loader.load();
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
                boutonAjouterEvent.getScene().setRoot(root);
            }    
        });
        
        
        
    }    

    @FXML
    private void eventAjouter(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("AjouterEvenement.fxml"));
        Pane root = loader.load();
        boutonAjouterEvent.getScene().setRoot(root);
    }
    
    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("GestionMvt.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }

    
}
