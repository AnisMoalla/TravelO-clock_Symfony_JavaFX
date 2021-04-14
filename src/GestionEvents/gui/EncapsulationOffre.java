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
public class EncapsulationOffre {
    private static int id;
    private static int evenement_id;
    private static int user_id;
    private static String nom;
    private static Date date_debut;
    private static Date date_fin;
    private static int pourcentage;

    public EncapsulationOffre() {
    }
    
    public EncapsulationOffre(String nom, Date date_debut, Date date_fin, int pourcentage) {
        this.nom = nom;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
        this.pourcentage = pourcentage;
    }

    public static int getId() {
        return id;
    }

    public static int getEvenement_id() {
        return evenement_id;
    }

    public static int getUser_id() {
        return user_id;
    }

    public static String getNom() {
        return nom;
    }

    public static Date getDate_debut() {
        return date_debut;
    }

    public static Date getDate_fin() {
        return date_fin;
    }

    public static int getPourcentage() {
        return pourcentage;
    }

    public static void setId(int id) {
        EncapsulationOffre.id = id;
    }

    public static void setEvenement_id(int evenement_id) {
        EncapsulationOffre.evenement_id = evenement_id;
    }

    public static void setUser_id(int user_id) {
        EncapsulationOffre.user_id = user_id;
    }

    public static void setNom(String nom) {
        EncapsulationOffre.nom = nom;
    }

    public static void setDate_debut(Date date_debut) {
        EncapsulationOffre.date_debut = date_debut;
    }

    public static void setDate_fin(Date date_fin) {
        EncapsulationOffre.date_fin = date_fin;
    }

    public static void setPourcentage(int pourcentage) {
        EncapsulationOffre.pourcentage = pourcentage;
    }
    
    
    
}
