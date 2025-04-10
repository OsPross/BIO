Sprawozdanie z tworzenia strony internetowej
Wstęp
Celem projektu było stworzenie strony internetowej z funkcjonalnościami umożliwiającymi zarządzanie profilem użytkownika. Strona miała zawierać podstawowe elementy, 
takie jak wyświetlanie daty i godziny, interaktywne elementy (np. migające gwiazdki), a także funkcje logowania i rejestracji z bazą danych. Na końcu strona miała umożliwić 
użytkownikom dodawanie odnośników do swoich profili w mediach społecznościowych i edytowanie swoich danych.



1. Tworzenie początkowego zarysu strony
Na początku stworzyłem prosty szkielet strony, który zawierał sekcję profilu użytkownika. Użyłem do tego HTML, tworząc odpowiednie elementy, takie jak nagłówek, 
sekcja z danymi użytkownika oraz miejsce na dane kontaktowe i odnośniki do mediów społecznościowych.

2. Stworzenie stylu strony przy użyciu CSS
Kolejnym krokiem było stworzenie stylów CSS, które nadały stronie atrakcyjny wygląd. Zastosowałem zasady dotyczące układu, kolorów, czcionek oraz rozmiarów elementów, 
aby strona była czytelna i przyjemna w użytkowaniu. Dodałem również responsywność, aby strona działała dobrze na różnych urządzeniach.

3. Dodanie skryptu wyświetlającego datę i godzinę
Aby strona była bardziej interaktywna, dodałem skrypt w JavaScript, który dynamicznie wyświetlał aktualną datę i godzinę. 
Skrypt odświeżał się w czasie rzeczywistym, zapewniając, że użytkownicy zawsze widzieli najnowsze informacje.

4. Tworzenie migających gwiazdek
Następnie za pomocą JavaScript stworzyłem efekt migających gwiazdek na stronie, co miało na celu wzbogacenie wyglądu strony o dynamiczne elementy. 
Użyłem prostych animacji CSS oraz manipulacji DOM w JavaScript, aby uzyskać pożądany efekt.

5. Dodanie systemu logowania w PHP
W kolejnej fazie projektu dodałem funkcjonalność logowania użytkowników. Wykorzystałem PHP, aby stworzyć formularz logowania, 
który sprawdzał poprawność danych użytkownika i umożliwiał dostęp do strony tylko zalogowanym osobom.

6. Integracja z bazą danych SQL
Następnie rozwinąłem system logowania, tworząc połączenie z bazą danych SQL. Logowanie opierało się teraz na danych przechowywanych w bazie, 
a użytkownik musiał podać poprawny login i hasło, które były weryfikowane względem zapisanych w bazie danych rekordów.

7.Dodanie funkcjonalności rejestracji i haszowania haseł
Kolejnym krokiem było stworzenie systemu rejestracji, który umożliwiał nowym użytkownikom założenie konta. Dodałem mechanizm haszowania haseł za pomocą funkcji 
password_hash(), co zwiększyło bezpieczeństwo strony i zapobiegało wykradzeniu danych uwierzytelniających.

8. Dodanie możliwości edytowania profilu
Na końcu przekształciłem stronę w pełnoprawny profil użytkownika, umożliwiając dodawanie odnośników do mediów społecznościowych (Instagram, GitHub, Discord). 
Użytkownik mógł także zmieniać swój opis profilu, a dane były automatycznie pobierane z bazy danych, co pozwalało na łatwą edycję i aktualizowanie informacji.