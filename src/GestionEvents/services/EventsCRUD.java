package GestionEvents.services;

import GestionEvents.entites.Evenement;
import GestionEvents.tools.MyConnection;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author legion
 */
public class EventsCRUD implements InterfaceEvents<Evenement> {

    Connection cnx = MyConnection.getInstance().getCnx();
    @Override
    public void ajouterEvent(Evenement e) {
        try {
            String requete = "INSERT INTO evenement (user_id, nom, date_debut, date_fin, description, pays, ville, image, prix, nbr_places) "
                    + "VALUES ('" + e.getUser_id()+ "','" + e.getNom()+ "','" 
                    + e.getDate_debut()+"','"+e.getDate_fin()+"','"
                    +e.getDescription()+"','"+e.getPays()+"','"
                    +e.getVille()+"','"+e.getImage()+"','"
                    +e.getPrix()+"','"+e.getNbr_places()+"')";
            Statement st = cnx.createStatement();
            st.executeUpdate(requete);
            System.out.println("Evenement Ajouteé !! ");

        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    }

    @Override
    public void supprimerEvent(Evenement e) {
        try {
            
            String requete = "DELETE FROM evenement WHERE nom like '" + e.getNom()+ "' AND date_debut like '"+e.getDate_debut()
                    +"' AND date_fin like '" + e.getDate_fin() + "' AND description like '" + e.getDescription() 
                    +"' AND pays like '" + e.getPays() + "' AND ville like '" + e.getVille() 
                    +"' AND prix = '" + e.getPrix() + "' AND nbr_places = '" + e.getNbr_places()+"';";
            Statement st = cnx.createStatement();
            st.executeUpdate(requete);
            System.out.println("Evenement Supprimée !! ");

        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    }

    @Override
    public void modifierEvent(Evenement e1,Evenement e2) {
        try {
            String requete = "UPDATE evenement SET "
                    + "nom = '" + e2.getNom() +"', date_debut = '"+ e2.getDate_debut()+"', date_fin = '" + e2.getDate_fin() 
                    + "',description = '" + e2.getDescription() 
                    +"' , pays = '" + e2.getPays() + "' , ville = '" + e2.getVille() 
                    +"' ,prix = '" + e2.getPrix() + "' , nbr_places = '" + e2.getNbr_places()
                    +"' WHERE nom like '" + e1.getNom()+ "' AND date_debut like '"+e1.getDate_debut()
                    +"' AND date_fin like '" + e1.getDate_fin() + "' AND description like '" + e1.getDescription() 
                    +"' AND pays like '" + e1.getPays() + "' AND ville like '" + e1.getVille() 
                    +"' AND prix = '" + e1.getPrix() + "' AND nbr_places = '" + e1.getNbr_places()+"';";
            Statement st = cnx.createStatement();
            st.executeUpdate(requete);
            System.out.println("Evenement modifie !! ");

        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    }

    @Override
    public List<Evenement> afficherEvent() {
        List<Evenement> List = new ArrayList<>();
        try {
            String requete = "SELECT evenement.id, user.prenom,evenement.nom,evenement.date_debut, evenement.date_fin, evenement.description, evenement.pays, evenement.ville, evenement.prix, evenement.nbr_places, evenement.rate"
                    + " FROM user,evenement"
                    + " where user.id=evenement.user_id";
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(requete);
            while(rs.next()) {
            List.add(new Evenement(rs.getInt(1),rs.getString(2), rs.getString(3), rs.getDate(4), rs.getDate(5), rs.getString(6), rs.getString(7), rs.getString(8), rs.getDouble(9), rs.getInt(10), rs.getDouble(11)));
            }
            
            System.out.println("Evennement affichée !! ");
            
        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
        return List;
    }

    @Override
    public double recupererPrixEvent(int id) {
        double prixTemp=0;
        try {
            String requete = "SELECT prix FROM evenement where id="+id;
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(requete);
            if(rs.next())
            {
                prixTemp=(rs.getInt(1));
            }        
        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
        return prixTemp;
    }

    @Override
    public void modifierNbrPlace(int id) {
        int placeTemp=0;
        try {
            String requete = "SELECT nbr_places FROM evenement where id='"+id+"'";
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(requete);
            if(rs.next())
            {
                placeTemp=(rs.getInt(1));
            }
            placeTemp--;
            requete="UPDATE evenement SET nbr_places='"+placeTemp+"' where id='"+id+"'";
            st.executeUpdate(requete);
            System.out.println("nbr_place modifie !! ");
        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    }

    @Override
    public Evenement getEventById(int id) {
        Evenement event = null;
        try {
            String requete = "SELECT evenement.id, user.prenom,evenement.nom,evenement.date_debut, evenement.date_fin, evenement.description, evenement.pays, evenement.ville, evenement.prix, evenement.nbr_places, evenement.rate, evenement.vote,evenement.image"
                    + " FROM user,evenement"
                    + " where user.id=evenement.user_id and evenement.id='"+id+"'";
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(requete);
            while(rs.next()) {
            event=new Evenement(rs.getInt(1),rs.getString(2), rs.getString(3), rs.getDate(4), rs.getDate(5), rs.getString(6), rs.getString(7), rs.getString(8), rs.getDouble(9), rs.getInt(10), rs.getDouble(11),rs.getInt(12),rs.getString(13));
            }
            
            System.out.println("Evennement affichée !! ");
            
        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
        return event;
    }

    @Override
    public void voter(Evenement e) {
        try {
            String requete = "UPDATE evenement SET "
                    + "rate = '" + e.getRate()+"', vote = '"+ e.getVote()
                    +"' WHERE id ='" + e.getId()+"';";
            Statement st = cnx.createStatement();
            st.executeUpdate(requete);
            System.out.println("Evenement Vote !! ");

        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
    }
    
}
