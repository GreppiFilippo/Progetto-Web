# Analisi Migliorie e Potenziali Bug - Mensa Campus

## 🔴 Bug Critici e Problemi di Sicurezza

### 1. **Password in chiaro nel database**
- **File:** `db/database.php` (metodo `checkLogin`, `createUser`)
- **Problema:** Le password vengono salvate e confrontate in chiaro senza hashing
- **Rischio:** CRITICO - In caso di breach del database, tutte le password sono compromesse
- **Soluzione:** Usare `password_hash()` per salvare e `password_verify()` per verificare
```php
// Invece di:
$password = $_POST["password"];
// Usare:
$hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
```

### 2. **Mancanza di protezione CSRF**
- **File:** Tutti i form (`register.php`, `login.php`, `user-bookings.php`, ecc.)
- **Problema:** Nessun token CSRF nei form
- **Rischio:** ALTO - Attacchi CSRF possibili
- **Soluzione:** Implementare token CSRF in tutti i form

### 3. **SQL Injection parzialmente mitigato ma incompleto**
- **File:** `db/database.php`
- **Problema:** Uso di prepared statements (bene) ma alcuni input non validati prima dell'uso
- **Soluzione:** Validare sempre gli input prima di usarli nelle query

### 4. **Session fixation vulnerability**
- **File:** `utils/functions.php` (funzione `registerLoggedUser`)
- **Problema:** Non viene rigenerato l'ID di sessione dopo il login
- **Rischio:** MEDIO - Possibile session fixation
- **Soluzione:** Aggiungere `session_regenerate_id(true);` dopo il login riuscito

### 5. **Mancanza di rate limiting**
- **File:** `login.php`, `register.php`
- **Problema:** Nessuna protezione contro attacchi brute-force
- **Rischio:** ALTO - Possibile brute-force di password
- **Soluzione:** Implementare rate limiting sui tentativi di login

---

## 🟠 Problemi di Validazione e Input

### 6. **Validazione email duplicata**
- **File:** `register.php` (righe 25-29)
- **Problema:** La validazione email è duplicata due volte
```php
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Il formato dell'email non è valido.";    
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // DUPLICATO
    $errors[] = "Il formato dell'email non è valido.";    
}
```
- **Soluzione:** Rimuovere la riga duplicata

### 7. **Mancanza di validazione lunghezza password**
- **File:** `register.php`
- **Problema:** Non viene controllata la lunghezza minima della password
- **Soluzione:** Aggiungere validazione (es. minimo 8 caratteri)

### 8. **Validazione stock debole**
- **File:** `user-bookings.php`
- **Problema:** La validazione dello stock viene fatta solo lato DB, non prima
- **Rischio:** Race condition se due utenti prenotano contemporaneamente l'ultimo piatto
- **Soluzione:** Implementare locking ottimistico o validazione preventiva

### 9. **Input non sanificati nei file di upload**
- **File:** `utils/functions.php` (funzione `uploadImage`)
- **Problema:** Il nome del file potrebbe contenere caratteri pericolosi
- **Soluzione:** Sanificare il nome del file più rigorosamente

### 10. **Mancanza di validazione tipo MIME nel backend**
- **File:** `utils/functions.php` (funzione `uploadImage`)
- **Problema:** La validazione dell'immagine usa solo `getimagesize()` che può essere bypassata
- **Soluzione:** Validare anche il MIME type con `mime_content_type()` o `finfo_file()`

---

## 🟡 Problemi di Logica e Business Rules

### 11. **Mancanza di restore dello stock in caso di cancellazione**
- **File:** `db/database.php` (metodo `deleteReservation`)
- **Problema:** Quando una prenotazione viene annullata, lo stock non viene ripristinato
- **Impatto:** I piatti rimangono "prenotati" anche se l'ordine è annullato
- **Soluzione:** Aggiungere UPDATE per ripristinare lo stock
```php
$updStock = $this->db->prepare(
    "UPDATE dishes d
     JOIN reservation_dishes rd ON d.dish_id = rd.dish_id
     SET d.stock = d.stock + rd.quantity
     WHERE rd.reservation_id = ?"
);
```

### 12. **Prenotazioni nel passato non controllate**
- **File:** `user-bookings.php`
- **Problema:** Il controllo della data/ora è solo lato client e in `get-time-slots.php`
- **Soluzione:** Aggiungere validazione anche nel backend durante `setNewReservation`

### 13. **Admin panel non funzionale**
- **File:** `template/content-admin-dashboard.php`, `content-admin-menu.php`, `content-admin-bookings.php`
- **Problema:** Le pagine admin contengono solo dati statici hardcoded e form non funzionanti
- **Soluzione:** Implementare la logica PHP per recuperare dati reali dal database

### 14. **Mancanza di paginazione**
- **File:** `user-dashboard.php`, `menu.php`
- **Problema:** Se ci sono molte prenotazioni/piatti, vengono caricati tutti in memoria
- **Soluzione:** Implementare paginazione o lazy loading

### 15. **Stato "Da Visualizzare" poco chiaro**
- **File:** `db/database.php` (metodo `setNewReservation`)
- **Problema:** Lo stato iniziale "Da Visualizzare" non è chiaro semanticamente
- **Suggerimento:** Rinominare in "In Attesa" o "Confermata"

---

## 🔵 Problemi di UX/UI

### 16. **Nessun feedback di caricamento**
- **File:** `js/user-bookings.js`
- **Problema:** Durante il fetch degli slot non c'è feedback visivo
- **Soluzione:** Aggiungere spinner o loading state

### 17. **Messaggi di errore generici**
- **File:** `login.php`, `register.php`
- **Problema:** "Errore! Controllare username o password!" non specifica quale campo è errato
- **Soluzione:** Fornire messaggi più specifici (mantenendo sicurezza per il login)

### 18. **Mancanza di conferma prima di cancellare**
- **File:** `template/user-dashboard-content.php`, `single-order-content.php`
- **Problema:** Il pulsante "Annulla ordine" cancella senza conferma
- **Soluzione:** Aggiungere modal di conferma JavaScript

### 19. **Filtri menu non persistenti**
- **File:** `template/content-menu.php`
- **Problema:** Se l'utente filtra e poi naviga altrove, i filtri si perdono
- **Soluzione:** Salvare filtri in sessionStorage o URL params

### 20. **Debounce troppo breve**
- **File:** `js/menu.js`
- **Problema:** Timer di 400ms potrebbe essere troppo breve per utenti lenti
- **Soluzione:** Aumentare a 600-800ms

---

## 🟢 Code Quality e Manutenibilità

### 21. **Tipo di ritorno non corretto in uploadImage**
- **File:** `admin-add-dish.php` (riga 59)
- **Problema:** `uploadImage` ritorna un array, ma viene usato con `list($result, $msg)`
- **Attuale:** Ritorna `['result' => ..., 'msg' => ..., 'filename' => ...]`
- **Usato come:** `list($result, $msg) = uploadImage(...)`
- **Soluzione:** Cambiare chiamata in:
```php
$uploadResult = uploadImage(UPLOAD_DIR, $_FILES["dishImage"]);
if ($uploadResult['result'] == 0) {
    $errors[] = $uploadResult['msg'];
} else {
    $imageName = $uploadResult['filename'];
}
```

### 22. **Variabile $dbh globale implicita**
- **File:** Tutti i file PHP
- **Problema:** `$dbh` viene creata in `bootstrap.php` ma usata ovunque senza dichiarazione
- **Soluzione:** Passare come parametro o usare dependency injection

### 23. **Mancanza di type hints completi**
- **File:** `db/database.php`, `utils/functions.php`
- **Problema:** Molte funzioni non hanno type hints completi per parametri e return
- **Soluzione:** Aggiungere type hints PHP 8+ per migliorare type safety

### 24. **Errori PHPStan (434 errori)**
- **File:** `potential_php_errors.txt`
- **Problema:** Molti problemi di tipo, offset non accessibili, variabili potenzialmente non definite
- **Soluzione:** Gradualmente correggere i warning di PHPStan partendo dai più critici

### 25. **Codice duplicato per navigation**
- **File:** `index.php`, `menu.php`, `user-dashboard.php`, ecc.
- **Problema:** La creazione del nav_items è duplicata in molti file
- **Soluzione:** Creare una funzione helper centralizzata

### 26. **Magic numbers e stringhe hardcoded**
- **File:** Vari
- **Problema:** Stati come "Da Visualizzare", "In Preparazione" sono hardcoded
- **Soluzione:** Usare costanti o enum
```php
class ReservationStatus {
    const TO_VIEW = 'Da Visualizzare';
    const IN_PREPARATION = 'In Preparazione';
    const READY = 'Pronto al ritiro';
    const COMPLETED = 'Completato';
    const CANCELLED = 'Annullato';
}
```

---

## 🟣 Performance

### 27. **Query N+1 nel dashboard**
- **File:** `user-dashboard.php` (righe 57-60)
- **Problema:** Per ogni prenotazione viene fatta una query separata per gli items
```php
foreach ($reservations as &$r) {
    $r['items'] = $dbh->getReservationItems((int)$r['reservation_id']);
}
```
- **Soluzione:** Fare una query JOIN o fetch di tutti gli items in una volta

### 28. **Nessuna cache per le categorie**
- **File:** Vari
- **Problema:** Le categorie vengono fetchate ogni volta da DB
- **Soluzione:** Implementare caching (APCu, Memcached, o semplice file cache)

### 29. **Rendering lato server inefficiente**
- **File:** `template/base-user.php` (righe 70-82)
- **Problema:** Il contenuto viene buffered per analizzare gli heading, rallenta il rendering
- **Soluzione:** Pre-calcolare o rimuovere questa logica

---

## 🟤 Accessibilità

### 30. **ARIA labels inconsistenti**
- **File:** Vari template
- **Problema:** Alcuni elementi hanno `aria-hidden="true"` ma non sempre coerente
- **Soluzione:** Review completo dell'accessibilità

### 31. **Contrasto colori insufficiente**
- **File:** CSS (non visto ma potenziale problema)
- **Soluzione:** Verificare contrasto WCAG AA

### 32. **Form labels non sempre associati**
- **File:** Alcuni template form
- **Problema:** Alcuni input potrebbero non avere label correttamente associato
- **Soluzione:** Verificare tutti i form con screen reader

---

## ⚫ Funzionalità Mancanti

### 33. **Nessun sistema di notifiche**
- **Problema:** L'utente non viene notificato quando l'ordine cambia stato
- **Soluzione:** Implementare notifiche email o push

### 34. **Nessuna conferma email alla registrazione**
- **File:** `register.php`
- **Problema:** Non viene inviata email di verifica
- **Soluzione:** Implementare email verification

### 35. **Mancanza di recupero password**
- **Problema:** Se l'utente dimentica la password, non può recuperarla
- **Soluzione:** Implementare "Forgot password" flow

### 36. **Nessun report per admin**
- **Problema:** Admin non può generare report di vendite, piatti più venduti, ecc.
- **Soluzione:** Implementare dashboard analytics

### 37. **Gestione stock non real-time**
- **Problema:** Lo stock non si aggiorna in tempo reale per gli utenti che stanno navigando
- **Soluzione:** Implementare WebSocket o polling per aggiornamenti real-time

### 38. **Nessuna gestione di allergie/intolleranze nella ricerca**
- **File:** `menu.php`
- **Problema:** Non è possibile filtrare i piatti per escludere allergeni
- **Soluzione:** Aggiungere filtro per dietary specifications

### 39. **Mancanza di sistema di recensioni/feedback**
- **Problema:** Gli utenti non possono lasciare feedback sui piatti
- **Soluzione:** Implementare sistema di rating

### 40. **Admin non può modificare/eliminare piatti**
- **File:** `admin-menu.php`
- **Problema:** L'admin può solo aggiungere piatti, non modificarli o rimuoverli
- **Soluzione:** Implementare CRUD completo per i piatti

---

## ⚪ Problemi di Configurazione e Deploy

### 41. **Credenziali database hardcoded**
- **File:** `bootstrap.php` (riga 14)
```php
$dbh = new DatabaseHelper("localhost", "root", "", "cafeteria", 3306);
```
- **Problema:** Credenziali nel codice sorgente
- **Soluzione:** Usare file `.env` con libreria come `vlucas/phpdotenv`

### 42. **Error reporting in produzione**
- **File:** `bootstrap.php`
- **Problema:** Non viene disabilitato error display per produzione
- **Soluzione:** Aggiungere configurazione ambiente
```php
if (getenv('APP_ENV') === 'production') {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE);
}
```

### 43. **Mancanza di .htaccess per sicurezza**
- **Problema:** Directory come `upload/` potrebbero essere accessibili direttamente
- **Soluzione:** Aggiungere `.htaccess` per proteggere directory sensibili

### 44. **Nessun logging degli errori**
- **Problema:** Gli errori non vengono loggati in file
- **Soluzione:** Implementare error logging

### 45. **Mancanza di backup automatico database**
- **Problema:** Nessun sistema di backup
- **Soluzione:** Implementare cron job per backup periodici

---

## 🔶 Problemi Minori e Refactoring

### 46. **Commenti obsoleti o inutili**
- **File:** Vari
- **Esempio:** `<!-- Esempio di piatti selezionabili -->` in user-bookings-content.php
- **Soluzione:** Rimuovere commenti HTML obsoleti

### 47. **Variabile $showAll non sempre definita**
- **File:** `template/user-dashboard-content.php` (riga 119)
- **Problema:** Se si accede direttamente senza GET params, `$showAll` non è definita
- **Soluzione:** Definire sempre in `user-dashboard.php`

### 48. **Inconsistenza nei nomi delle variabili**
- **Problema:** A volte `$templateParams["categorie"]`, a volte `$templateParams["categories"]`
- **Soluzione:** Standardizzare nomenclatura (preferibilmente in inglese)

### 49. **Formatting inconsistente**
- **Problema:** A volte spazi, a volte no tra operatori
- **Soluzione:** Usare PHP-CS-Fixer o similare

### 50. **Missing semicolons in PHP**
- **File:** `admin-dashboard.php` (riga 27), `admin-menu.php` (riga 27), `admin-bookings.php` (riga 28)
- **Problema:** Manca punto e virgola alla fine del `require`
```php
require "template/base-admin.php" // MANCANTE ;
```
- **Soluzione:** Aggiungere il punto e virgola

---

## 📊 Riepilogo per Priorità

### 🔴 **PRIORITÀ CRITICA (da risolvere subito)**
1. Password in chiaro (#1)
2. Mancanza CSRF protection (#2)
3. Session fixation (#4)
4. Mancanza rate limiting (#5)
5. Stock non ripristinato su cancellazione (#11)

### 🟠 **PRIORITÀ ALTA (da risolvere presto)**
6. Validazione email duplicata (#6)
7. Validazione password debole (#7)
8. Race condition stock (#8)
9. Upload file non sicuro (#9-10)
10. Prenotazioni nel passato (#12)

### 🟡 **PRIORITÀ MEDIA (miglioramenti importanti)**
11. Admin panel non funzionale (#13)
12. Paginazione (#14)
13. Bug uploadImage (#21)
14. Variabile globale $dbh (#22)
15. Query N+1 (#27)

### 🟢 **PRIORITÀ BASSA (nice to have)**
16. Refactoring generale (#23-26)
17. Performance (#28-29)
18. Accessibilità (#30-32)
19. UX miglioramenti (#16-20)

### ⚪ **FUNZIONALITÀ FUTURE**
20. Notifiche (#33)
21. Email verification (#34)
22. Password recovery (#35)
23. Admin analytics (#36)
24. Real-time updates (#37)

---

## 🛠️ Checklist per Refactoring

- [ ] Implementare hashing password
- [ ] Aggiungere CSRF tokens
- [ ] Configurare variabili d'ambiente
- [ ] Fix restore stock su cancellazione
- [ ] Rimuovere validazione email duplicata
- [ ] Correggere uploadImage usage
- [ ] Implementare rate limiting
- [ ] Aggiungere session_regenerate_id
- [ ] Completare admin panel
- [ ] Implementare paginazione
- [ ] Aggiungere type hints
- [ ] Refactorare codice duplicato
- [ ] Aggiungere logging
- [ ] Implementare caching
- [ ] Ottimizzare query N+1
- [ ] Review accessibilità
- [ ] Aggiungere conferme UI
- [ ] Implementare notifiche
- [ ] Setup backup database
- [ ] Aggiungere .htaccess security
