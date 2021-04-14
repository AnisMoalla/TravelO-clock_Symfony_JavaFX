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
import java.sql.Date;
import java.util.List;
import java.util.ResourceBundle;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.Pane;

/**
 * FXML Controller class
 *
 * @author legion
 */
public class OffresBackController implements Initializable {

    @FXML
    private TableView<Offre> tableViewOffre;
    @FXML
    private TableColumn<Offre, String> colonneUsername; //Toujours remplacer les ? dans le diamond <>
    @FXML
    private TableColumn<Offre, String> colonneEvent;
    @FXML
    private TableColumn<Offre, String> colonneOffre;
    @FXML
    private TableColumn<Offre, Date> colonneDateDebut;
    @FXML
    private TableColumn<Offre, Date> colonneDateFin;
    @FXML
    private TableColumn<Offre, Integer> colonnePourcentage;
    @FXML
    private Button boutonAjouterOffre;
    @FXML
    private Button boutonRetour;
    private OffresCRUD offresCRUD;
    private List<Offre> offreList;


    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        offresCRUD = new OffresCRUD();
        offreList = offresCRUD.afficherOffre();
        
        colonneUsername.setCellValueFactory(new PropertyValueFactory("username")); //Dans le propertyValueFactory contien le nom
                                                                                   // de l'attribut dans l'entité
        colonneEvent.setCellValueFactory(new PropertyValueFactory("eventname"));
        colonneOffre.setCellValueFactory(new PropertyValueFactory("nom"));
        colonneDateDebut.setCellValueFactory(new PropertyValueFactory("date_debut"));
        colonneDateFin.setCellValueFactory(new PropertyValueFactory("date_fin"));
        colonnePourcentage.setCellValueFactory(new PropertyValueFactory("pourcentage"));
      
        for(int i=0;i<offreList.size();i++)
        {
           tableViewOffre.getItems().add(offreList.get(i));
        }
        
        
        
        tableViewOffre.setOnMouseClicked((javafx.scene.input.MouseEvent event) -> {
            if (event.getClickCount()==2) {
                Offre monOffre=tableViewOffre.getSelectionModel().getSelectedItem();
                EncapsulationOffre encapsulationOffre = new EncapsulationOffre(monOffre.getNom(),monOffre.getDate_debut(),monOffre.getDate_fin(),monOffre.getPourcentage());
                FXMLLoader loader = new FXMLLoader(OffresBackController.this.getClass().getResource("SuppModifOffre.fxml"));
                Pane root = null;
                try {
                    root = loader.load();
                } catch (IOException ex) {
                    ex.printStackTrace();
                }   boutonAjouterOffre.getScene().setRoot(root);
            }    
        });
    }    

    @FXML
    private void eventAjouter(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("Ajouter Offre.fxml"));
        Pane root = loader.load();
        boutonAjouterOffre.getScene().setRoot(root);
    }
    
    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("GestionMvt.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }
    
}
