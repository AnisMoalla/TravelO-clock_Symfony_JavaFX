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
public interface InterfaceOffres<O> {
    public void ajouterOffre(O o);
    public void supprimerOffre(O o);
    public void modifierOffre(O o1,O o2);
    public List<O> afficherOffre();
    
}
