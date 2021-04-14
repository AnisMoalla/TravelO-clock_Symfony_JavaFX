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
public class EncapsulationResrvation {
    private static int id;
    private static int evenement_id;
    private static int user_id;
    private static Date date_reservation;
    private static Double tarif;

    public EncapsulationResrvation() {
    }
    
    public EncapsulationResrvation(int user_id, Date date_reservation, Double tarif) {
        this.user_id=user_id;
        this.date_reservation=date_reservation;
        this.tarif=tarif;
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

    public static Date getDate_reservation() {
        return date_reservation;
    }

    public static Double getTarif() {
        return tarif;
    }

    public static void setId(int id) {
        EncapsulationResrvation.id = id;
    }

    public static void setEvenement_id(int evenement_id) {
        EncapsulationResrvation.evenement_id = evenement_id;
    }

    public static void setUser_id(int user_id) {
        EncapsulationResrvation.user_id = user_id;
    }

    public static void setDate_reservation(Date date_reservation) {
        EncapsulationResrvation.date_reservation = date_reservation;
    }

    public static void setTarif(Double tarif) {
        EncapsulationResrvation.tarif = tarif;
    }
    
    
    
}
