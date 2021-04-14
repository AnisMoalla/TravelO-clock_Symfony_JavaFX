/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.services;

import GestionEvents.entites.Offre;
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
public class OffresCRUD implements InterfaceOffres<Offre> {

    Connection cnx = MyConnection.getInstance().getCnx();
    @Override
    public void ajouterOffre(Offre o) {
        try {
            String requete = "INSERT INTO offre (evenement_id, user_id, nom, date_debut, date_fin, pourcentage) "
                    + "VALUES ('" + o.getEvenement_id()+ "','"+o.getUser_id()+"','" + o.getNom()+ "','" 
                    + o.getDate_debut()+"','"+o.getDate_fin()+"','"
                    +o.getPourcentage()+"');";
            Statement st = cnx.createStatement();
            st.executeUpdate(requete);
            System.out.println("Offre Ajouteé !! ");

        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    }

    @Override
    public void supprimerOffre(Offre o) {
        try {
            
            String requete = "DELETE FROM offre WHERE nom like '" + o.getNom()+ "' AND date_debut like '"+o.getDate_debut()
                    +"' AND date_fin like '" + o.getDate_fin() + "' AND pourcentage = '" + o.getPourcentage()+"';";
            Statement st = cnx.createStatement();
            st.executeUpdate(requete);
            System.out.println("Offre Supprimée !! ");

        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    }

    @Override
    public void modifierOffre(Offre o1, Offre o2) {
        try {
            String requete = "UPDATE offre SET "
                    + "nom = '" + o2.getNom() +"', date_debut = '"+ o2.getDate_debut()+"', date_fin = '" + o2.getDate_fin() 
                    + "',pourcentage = '" + o2.getPourcentage()
                    +"' WHERE nom like '" + o1.getNom()+ "' AND date_debut like '"+o1.getDate_debut()
                    +"' AND date_fin like '" + o1.getDate_fin() + "' AND pourcentage = '" + o1.getPourcentage()+"';";
            Statement st = cnx.createStatement();
            st.executeUpdate(requete);
            System.out.println("Offre modifie !! ");

        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    }

    @Override
    public List<Offre> afficherOffre() {
        List<Offre> List = new ArrayList<>();
        try {
            String requete = "SELECT user.prenom,evenement.nom,offre.nom ,offre.date_debut, offre.date_fin, offre.pourcentage"
                    + " FROM user,evenement,offre"
                    + " where user.id=offre.user_id and evenement.id=offre.evenement_id  ";
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(requete);
            while(rs.next()) {
            List.add(new Offre(rs.getString(1),rs.getString(2),rs.getString(3),rs.getDate(4),rs.getDate(5),rs.getInt(6)));
            }
            
            System.out.println("Offre affichée !! ");
            
        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
        return List;
    }
    
}
