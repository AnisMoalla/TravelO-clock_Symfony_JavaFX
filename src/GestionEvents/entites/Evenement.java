package GestionEvents.entites;

import java.sql.Date;

/**
 *
 * @author legion
 */
public class Evenement {
    private int id;
    private int user_id;
    private String nom;
    private Date date_debut;
    private Date date_fin;
    private String description;
    private String pays;
    private String ville;
    private String image;
    private double prix;
    private int nbr_places;
    private double rate;
    private int vote;
    private String username;
    

    public Evenement() {
    }
    
    public Evenement(String nom,Date date_debut,Date date_fin,String description,String pays,String ville,double prix,int nbr_places) {
        this.nom = nom;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.description = description;
        this.pays = pays;
        this.ville = ville;
        this.prix = prix;
        this.nbr_places = nbr_places;
    }
    
    public Evenement(int id, int user_id, String nom, Date date_debut, Date date_fin, String description, String pays, String ville, String image, double prix, int nbr_places, double rate, int vote) {
        this.id = id;
        this.user_id = user_id;
        this.nom = nom;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.description = description;
        this.pays = pays;
        this.ville = ville;
        this.image = image;
        this.prix = prix;
        this.nbr_places = nbr_places;
        this.rate = rate;
        this.vote = vote;
    }
    
    public Evenement(int user_id, String nom, Date date_debut, Date date_fin, String description, String pays, String ville, String image, double prix, int nbr_places) {
        this.user_id = user_id;
        this.nom = nom;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.description = description;
        this.pays = pays;
        this.ville = ville;
        this.image = image;
        this.prix = prix;
        this.nbr_places = nbr_places;
    }

    public Evenement(int id,String username, String nom, Date date_debut, Date date_fin, String description, String pays, String ville, double prix, int nbr_places, double rate) {
        this.id=id;
        this.nom = nom;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.description = description;
        this.pays = pays;
        this.ville = ville;
        this.prix = prix;
        this.nbr_places = nbr_places;
        this.rate = rate;
        this.username = username;
    }
    
    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getUser_id() {
        return user_id;
    }

    public void setUser_id(int user_id) {
        this.user_id = user_id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public Date getDate_debut() {
        return date_debut;
    }

    public void setDate_debut(Date date_debut) {
        this.date_debut = date_debut;
    }

    public Date getDate_fin() {
        return date_fin;
    }

    public void setDate_fin(Date date_fin) {
        this.date_fin = date_fin;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getPays() {
        return pays;
    }

    public void setPays(String pays) {
        this.pays = pays;
    }

    public String getVille() {
        return ville;
    }

    public void setVille(String ville) {
        this.ville = ville;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public double getPrix() {
        return prix;
    }

    public void setPrix(double prix) {
        this.prix = prix;
    }

    public int getNbr_places() {
        return nbr_places;
    }

    public void setNbr_places(int nbr_places) {
        this.nbr_places = nbr_places;
    }

    public double getRate() {
        return rate;
    }

    public void setRate(double rate) {
        this.rate = rate;
    }

    public int getVote() {
        return vote;
    }

    public void setVote(int vote) {
        this.vote = vote;
    }
    
    
    
    
    
}
