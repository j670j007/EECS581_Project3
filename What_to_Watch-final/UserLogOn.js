/* Team 17 - Brisa Andrade, 
* Project 3: MovieList , 581 Software Engineering II, 11.08.2024 -->
* UserLogON.css
* This file is made up of all Javascript Functions used to log a user into their account and log them out.
*/
        

        /*Opens up a pop up that asks a user if they wish to log into the service or sign up.
        * @param id is the id of the pop up the user wants to interact with
        * @
        */
        function openPopUp(id)
        {
            /*Ensures that all other pop ups are closed*/
            document.getElementById('accountStart').style.display="none";
            document.getElementById('userLoginForm').style.display="none";
            document.getElementById('userSignupForm').style.display="none";

            /*Opens on pop up user wants to interact with*/
            document.getElementById(id).style.display="block";
        } //end popUpLogin

        //Closes a Pop Up
        function popUpClose(id)
        {
            document.getElementById(id).style.display="none";
        }
        

        //close previous pop up, open new pop up 
        function userLogin()
        {
            popUpClose('accountStart');
            openPopUp('userLoginForm');
        } //end userLogin

        function userSignup()
        {
            popUpClose('accountStart');
            openPopUp('userSignupForm');
        }//end userSignup

        function logIn()
        {
            /*Closes user log in form*/
            popUpClose('userLoginForm');
            window.location.replace("index.html")
            let loggedin=true;   
        } //end logIn

        //logs a user out and takes them to the home page(only page accessible to logged out users
        function logOut()
        {
            window.location.replace("index.html")
        } //end LogOut
        
