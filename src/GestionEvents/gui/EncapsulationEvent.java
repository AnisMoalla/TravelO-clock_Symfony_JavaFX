/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.gui;

import java.sql.Date;

/**
 *
 * @author legion
 */
public class EncapsulationEvent {
    private static int id;
    private static String nom;
    private static Date date_debut;
    private static Date date_fin;
    private static String description;
    private static String pays;
    private static String ville;
    private static double prix;
    private static int nbr_places;

    public EncapsulationEvent(int id,String nom,Date date_debut,Date date_fin,String description,String pays,String ville,double prix,int nbr_places) {
        this.id=id;
        this.nom = nom;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.description = description;
        this.pays = pays;
        this.ville = ville;
        this.prix = prix;
        this.nbr_places = nbr_places;
    }

    public EncapsulationEvent() {
    }

    public static int getId() {
        return id;
    }

    public static void setId(int id) {
        EncapsulationEvent.id = id;
    }
    
    
    
    public static String getNom() {
        return nom;
    }

    public static void setNom(String nom) {
        EncapsulationEvent.nom = nom;
    }

    public static Date getDate_debut() {
        return date_debut;
    }

    public static void setDate_debut(Date date_debut) {
        EncapsulationEvent.date_debut = date_debut;
    }

    public static Date getDate_fin() {
        return date_fin;
    }

    public static void setDate_fin(Date date_fin) {
        EncapsulationEvent.date_fin = date_fin;
    }

    public static String getDescription() {
        return description;
    }

    public static void setDescription(String description) {
        EncapsulationEvent.description = description;
    }

    public static String getPays() {
        return pays;
    }

    public static void setPays(String pays) {
        EncapsulationEvent.pays = pays;
    }

    public static String getVille() {
        return ville;
    }

    public static void setVille(String ville) {
        EncapsulationEvent.ville = ville;
    }

    public static double getPrix() {
        return prix;
    }

    public static void setPrix(double prix) {
        EncapsulationEvent.prix = prix;
    }

    public static int getNbr_places() {
        return nbr_places;
    }

    public static void setNbr_places(int nbr_places) {
        EncapsulationEvent.nbr_places = nbr_places;
    }
    
    

    
}
