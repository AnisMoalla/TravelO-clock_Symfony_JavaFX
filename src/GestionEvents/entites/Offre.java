package GestionEvents.entites;

import java.sql.Date;

/**
 *
 * @author legion
 */
public class Offre {
    private int id;
    private int evenement_id;
    private int user_id;
    private String nom;
    private Date date_debut;
    private Date date_fin;
    private int pourcentage;
    private String username;
    private String eventname;

    public Offre() {
    }

    public Offre(int id, int evenement_id, int user_id, String nom, Date date_debut, Date date_fin, int pourcentage) {
        this.id = id;
        this.evenement_id = evenement_id;
        this.user_id = user_id;
        this.nom = nom;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.pourcentage = pourcentage;
    }

    public Offre( String nom, Date date_debut, Date date_fin, int pourcentage) {
        this.nom = nom;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.pourcentage = pourcentage;
    }

    public Offre(String username, String eventname, String nom, Date date_debut, Date date_fin, int pourcentage) {
        this.nom = nom;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.pourcentage = pourcentage;
        this.username = username;
        this.eventname = eventname;
    }


    
    
    public int getId() {
        return id;
    }

    public int getEvenement_id() {
        return evenement_id;
    }

    public int getUser_id() {
        return user_id;
    }

    public String getNom() {
        return nom;
    }

    public Date getDate_debut() {
        return date_debut;
    }

    public Date getDate_fin() {
        return date_fin;
    }

    public int getPourcentage() {
        return pourcentage;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setEvenement_id(int evenement_id) {
        this.evenement_id = evenement_id;
    }

    public void setUser_id(int user_id) {
        this.user_id = user_id;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public void setDate_debut(Date date_debut) {
        this.date_debut = date_debut;
    }

    public void setDate_fin(Date date_fin) {
        this.date_fin = date_fin;
    }

    public void setPourcentage(int pourcentage) {
        this.pourcentage = pourcentage;
    }

    public String getUsername() {
        return username;
    }

    public String getEventname() {
        return eventname;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public void setEventname(String eventname) {
        this.eventname = eventname;
    }
    
    
    
}
