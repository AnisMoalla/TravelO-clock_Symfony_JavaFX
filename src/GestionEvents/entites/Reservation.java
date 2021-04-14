/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.entites;

import java.sql.Date;

/**
 *
 * @author legion
 */
public class Reservation {
    private int id;
    private int tourist_id;
    private int evenement_id;
    private int offre_id;
    private Date date_reservation;
    private Double tarif;
    private String username;
    private String eventname;
    private String offrename;

    public Reservation() {
    }

    public Reservation(int id, int tourist_id, int evenement_id, int offre_id, Date date_reservation, Double tarif, String username, String eventname, String offrename) {
        this.id = id;
        this.tourist_id = tourist_id;
        this.evenement_id = evenement_id;
        this.offre_id = offre_id;
        this.date_reservation = date_reservation;
        this.tarif = tarif;
        this.username = username;
        this.eventname = eventname;
        this.offrename = offrename;
    }

    public Reservation(String username, String eventname, Date date_reservation, Double tarif) {
        this.date_reservation = date_reservation;
        this.tarif = tarif;
        this.username = username;
        this.eventname = eventname;
    }

//    public Reservation(int offre_id, Date date_reservation, Double tarif) {
//        this.offre_id = offre_id;
//        this.date_reservation = date_reservation;
//        this.tarif = tarif;
//    }
    
    public Reservation(int evenement_id, Date date_reservation, Double tarif) {
        this.evenement_id = evenement_id;
        this.date_reservation = date_reservation;
        this.tarif = tarif;
    }

    public int getId() {
        return id;
    }

    public int getTourist_id() {
        return tourist_id;
    }

    public int getEvenement_id() {
        return evenement_id;
    }

    public int getOffre_id() {
        return offre_id;
    }

    public Date getDate_reservation() {
        return date_reservation;
    }

    public Double getTarif() {
        return tarif;
    }

    public String getUsername() {
        return username;
    }

    public String getEventname() {
        return eventname;
    }

    public String getOffrename() {
        return offrename;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setTourist_id(int tourist_id) {
        this.tourist_id = tourist_id;
    }

    public void setEvenement_id(int evenement_id) {
        this.evenement_id = evenement_id;
    }

    public void setOffre_id(int offre_id) {
        this.offre_id = offre_id;
    }

    public void setDate_reservation(Date date_reservation) {
        this.date_reservation = date_reservation;
    }

    public void setTarif(Double tarif) {
        this.tarif = tarif;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public void setEventname(String eventname) {
        this.eventname = eventname;
    }

    public void setOffrename(String offrename) {
        this.offrename = offrename;
    }
    
    
    
    
}
