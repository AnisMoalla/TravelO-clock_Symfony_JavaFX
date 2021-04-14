package GestionEvents.services;

import GestionEvents.entites.Evenement;
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
            String requete = "SELECT user.prenom,evenement.nom,evenement.date_debut, evenement.date_fin, evenement.description, evenement.pays, evenement.ville, evenement.prix, evenement.nbr_places, evenement.rate"
                    + " FROM user,evenement"
                    + " where user.id=evenement.user_id";
            Statement st = cnx.createStatement();
            ResultSet rs = st.executeQuery(requete);
            while(rs.next()) {
            List.add(new Evenement(rs.getString(1),rs.getString(2),rs.getDate(3),rs.getDate(4),rs.getString(5),rs.getString(6),rs.getString(7),rs.getDouble(8),rs.getInt(9),rs.getDouble(10)));
            }
            
            System.out.println("Evennement affichée !! ");
            
        } catch (SQLException ex) {
            System.err.println(ex.getMessage());
        }
        return List;
    }
    
}
