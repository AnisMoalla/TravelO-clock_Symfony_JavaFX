package GestionEvents.tools;

/**
 *
 * @author legion
 */
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

/**
 *
 * @author Louay
 */
public class MyConnection {
    public String url = "jdbc:mysql://localhost:3306/travel";
    public String login = "root";
    public String pwd = "";

    public Connection cn;
    public static MyConnection instance;

    public MyConnection() {
        try {
            cn = DriverManager.getConnection(url, login, pwd);
            System.out.println("Connection etablit");
        } catch (SQLException ex) {
            System.out.println("Erreur de connexion");
            System.out.println(ex.getMessage());
        }

    }

    public static MyConnection getInstance() {
        if (instance == null) {
            instance = new MyConnection();
        }
        return instance;
    }

    public Connection getCnx() {
        return cn;
    }

    
}

