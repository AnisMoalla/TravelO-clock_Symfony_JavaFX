/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.gui;

import GestionEvents.tools.MyConnection;
import java.io.IOException;
import java.net.URL;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.ResourceBundle;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.fxml.Initializable;
import javafx.scene.chart.PieChart;
import javafx.scene.control.Button;
import javafx.scene.layout.Pane;

/**
 * FXML Controller class
 *
 * @author legion
 */
public class StatEventController implements Initializable {

    @FXML
    private PieChart pieChart;
    @FXML
    private Button boutonRetour;
    

    
    /**
     * Initializes the controller class.
     */
    @Override
    public void initialize(URL url, ResourceBundle rb) {
       
        ObservableList<PieChart.Data> pieChartData =
                FXCollections.observableArrayList();
               
                try {
            System.out.println("hello there");
            String requete = "SELECT  UPPER(evenement.nom) as nom ,COUNT(offre.id) as num FROM offre \n" +
"        join evenement  WHERE evenement.id=offre.evenement_id GROUP BY offre.evenement_id ";
            Statement st = MyConnection.getInstance().getCnx().createStatement();
            ResultSet rs = st.executeQuery(requete);
            while (rs.next()) {
                
                pieChartData.add(new PieChart.Data(rs.getString("nom"), rs.getInt("num")));
                
            }

        } catch (SQLException ex) {
            System.out.println(ex.getMessage());
        }
                
                
                pieChart.setTitle("Stat Events Offres");
                pieChart.getData().addAll(pieChartData); 
    }    

    @FXML
    private void actionRetour(ActionEvent event) throws IOException {
        FXMLLoader loader = new FXMLLoader(getClass().getResource("EventsBack.fxml"));
        Pane root = loader.load();
        boutonRetour.getScene().setRoot(root);
    }
    
    
    
}
