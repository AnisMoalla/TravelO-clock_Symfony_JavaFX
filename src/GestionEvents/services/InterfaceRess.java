/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GestionEvents.services;

import java.util.List;

/**
 *
 * @author legion
 */
public interface InterfaceRess<R> {
    public void ajouterReservation(R r);
    public void supprimerReservation(R r);
    public void modifierReservation(R r1,R r2);
    public List<R> afficherReservation();
    
}
