/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.services;

import GestionEvents.entites.Reservation;
import GestionEvents.tools.MyConnection;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;

/**
 *
 * @author legion
 */
public class RessCRUD implements InterfaceRess<Reservation> {

    Connection cnx = MyConnection.getInstance().getCnx();
    @Override
    public void ajouterReservation(Reservation r) {
        try {
            System.out.println("__________________________________________________!! ");
            String requete = "INSERT INTO reservation (tourist_id, evenement_id, offre_id, date_reservation, tarif) "
                    + "VALUES ('" + r.getTourist_id()+ "','"+r.getEvenement_id()+"','" + r.getOffre_id()+ "','" 
                    + r.getDate_reservation()+"','"
                    +r.getTarif()+"');";
            Statement st = cnx.createStatement();
            st.executeUpdate(requete);
            System.out.println("Reservation Ajouteé !! ");

        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    }

    @Override
    public void supprimerReservation(Reservation r) {
        try {
            
            String requete = "DELETE FROM reservation WHERE evenement_id = '" + r.getEvenement_id()+ "' AND date_reservation like '"+r.getDate_reservation()
                    +"' AND tarif = '" + r.getTarif()+"';";
            Statement st = cnx.createStatement();
            st.executeUpdate(requete);
            System.out.println("Reservation Supprimée !! ");

        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    }

    @Override
    public void modifierReservation(Reservation r1, Reservation r2) {
        
    }

    @Override
    public List<Reservation> afficherReservation() {
        List<Reservation> List = new ArrayList<>();
        try {
            String requete = "SELECT user.prenom, evenement.nom, reservation.date_reservation ,reservation.tarif"
                    + " FROM user,evenement,reservation"
                    + " where user.id=evenement.user_id and evenement.id=reservation.evenement_id  ";
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(requete);
            while(rs.next()) {
            List.add(new Reservation(rs.getString(1),rs.getString(2),rs.getDate(3),rs.getDouble(4)));
            }
            
            System.out.println("Reservation affichée !! ");
            
        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
        return List;
    }
    
    
}
