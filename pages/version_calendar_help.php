<?php

# Includes

require_once('version_calendar_api.php');

# Page header

html_page_top(plugin_lang_get('main_menu'));
print_submenu();

# Help content
?>
<h2>Zusammenfassung</h2>

<p>Dieses Plugin stellt im Mantis-Hauptmenü einen <em>Versionskalender</em> zur Verfügung, um allen Projektbeteiligten einen schnellen und einfachen Überblick aller Release-relevanten Termine zu geben.</p>

<h2>Vorteile</h2>

<ul>
<li>Integration eines Kalenders zur Releaseplanung in Mantis (anstatt zwei getrennter Werkzeuge)</li>
<li>Die Jahresansicht gibt einen besseren und schnelleren Überblick als die Monatsansichten anderer Kalender (oder die Jahresansicht verschiedener Kalender-Plugins, welche Termindetails nicht direkt anzeigen)</li>
<li>Zentraler Zugriff für alle Projektbeteiligten möglich, auch rückwirkend und für neue Beteiligte (was umständlich ist, wenn die Termine als Einladungen per Email verschickt werden)</li>
<li>Es werden nicht nur die Release-Termine, sondern auch die entsprechenden Feature Freezes und Code Freezes angezeigt</li>
<li>In einem Tooltip können weitere Informationen angezeigt werden, z.&nbsp;B. Liefertermine, verschobene Termine sowie SVN-Tags und -Branches (wichtig für Entwickler)</li>
</ul>

<p>Durch den Versionskalender kann die Arbeit mit Mantis wie folgt optimiert werden:
<ol>
<li>Mantis-Anmeldung &rArr; Versionskalender (Überblick über die anstehenden Termine)</li>
<li>Klick auf eine Version &rArr; Einträge-Liste (Überblick über den aktuellen Stand dieser Version)</li>
<li>Klick auf einen Eintrag &rArr; Eintrags-Seite (Detailinformationen über diesen Eintrag)</li>
</ol>
</p>

<h2>Bildschirmfoto</h2>

<img src="<?php echo plugin_file('version_calendar.png'); ?>"/>
<h2>Funktionen</h2>

<p>Der Versionskalender zeigt konfigurierbare Termine für alle Versionen des aktuellen Projekts an.
Es ist nicht möglich, Termine für mehr als ein Projekt gleichzeitig anzuzeigen.</p>

<p>Die Darstellung erfolgt in einem Jahreskalender mit Hervorhebung des aktuellen Datums
und den in München gültigen gesetzlichen Feiertagen sowie den in der Regel arbeitsfreien Tagen Heiligabend und Silvester.</p>

<p>Es werden immer alle Termine aller Versionen des aktuelles Projekts angezeigt.
Erstrecken diese sich über mehrere Jahre, so werden mehrere Jahreskalender übereinander angezeigt, beginnend mit dem neuesten.
Es gibt keine Filter.</p>

<p>Jeder Version wird per Modulo-Arithmetik eine von sechs verschiedenen Farben zugewiesen.</p>

<p>Schwebt man mit der Maus über einem (beliebigen) Termin einer Version, so wird der gesamte Beschreibungstext der entsprechenden Version in Form eines Tooltips angezeigt.</p>

<p>Klickt man auf einen (beliebigen) Termin einer Version, so gelangt man zur Seite <em>Einträge anzeigen</em> mit folgenden Filtereinstellungen:
<ul>
<li>Zielversion: Die gewählte Version</li>
<li>Sortieren nach: Status (aufsteigend)</li>
<li>Status ausblenden: keine (auch nicht <em>geschlossen</em>)</li>
<li>Zeige (Einträge pro Seite): 999.999</li>
<li>Fixierte Einträge anzeigen: Ja</li>
</ul>
</p>

<p>Analog dazu gibt es im Submenü den Filter <em>Ungeplante Einträge</em>, der alle Einträge ohne Zielversion anzeigt (restliche Filtereinstellungen analog zu oben):
<ul>
<li>Zielversion: keine</li>
</ul>
</p>

<p>Außerdem gibt es im Submenü den Filter <em>Alle Einträge</em>, der alle Einträge ohne Einschränkung der Zielversion anzeigt
und als Ausgangspunkt für weitere, manuelle Filtereinstellungen dienen kann (restliche Filtereinstellungen analog zu oben):
<ul>
<li>Zielversion: alle</li>
<li>Sortieren nach: Zielversion (aufsteigend), Status (aufsteigend)</li>
</ul>
</p>

<h2>Verwaltung der Termine</h2>

<p>Die Termine werden in den Beschreibungstexten der Projektversionen verwaltet.
(Verwaltung &rarr; Projekte verwalten &rarr; <em>Projekt</em> &rarr; Versionen &rarr; Bearbeiten &rarr; Beschreibung)</p>

<p>Dies hat zur Folge, daß man (in der Mantis-Standardkonfiguration) mindestens Manager-Rechte braucht, um Termine zu verwalten.</p>

<p>Der Versionskalender parst jede Zeile des Beschreibungstexts wie folgt: <em>Stern Datum Kolon Text [Kommentar]</em>,
wobei Datum im Format <em>TT. MM. YYYY</em> ist und zwischen den einzelnen Elementen Leerzeichen vorkommen dürfen.
Kommentare sind optional, beginnen mit <em>&lt;--</em> und enden am Zeilenende.</p>

<p>Der genaue reguläre Ausdruck lautet: <code>/^\s*\*\s*(\d\d)\.\s*(\d\d)\.\s*(\d\d\d\d)\s*\:(.*?)(?:<--.*)?$/m</code></p>

<p>Wenn der <em>Text</em> (bis auf Groß-/Kleinschreibung) exakt einem der Schlüsselwörter <em>Alpha</em>, <em>Feature Freeze</em>, <em>Beta</em> oder <em>Release</em> entspricht,
so werden die entsprechenden Termine als <em>α</em>, <em>ff</em>, <em>β</em> bzw. <em>r</em> im Versionskalender dargestellt.
Die Schlüsselwörter und deren Darstellungen im Kalender sind konfigurierbar.
Alle anderen Texte werden ignoriert.
Zeilen, die nicht dem regulären Ausdruck entsprechen, werden ebenfalls ignoriert.</p>

<p>Beispiel für den Beschreibungstext der Version 6.6.0 aus obigem Bildschirmfoto:
<p class="code block">SVN:
* Development: trunk
* Release: Noch nicht releast

Chronik:
* 01.03.2013: Start Implementierung
* 03.04.2013: Alpha <-- V6_6_0_R617
* 11.04.2013: Feature Freeze
* 16.04.2013: Release -- verschoben
* 18.04.2013: Beta
* 14.05.2013: Release</p>
</p>

<p>In diesem Beispiel werden folgende vier Termine im Versionskalender angezeigt:
<ul>
<li>03.04.2013: Alpha</li>
<li>11.04.2013: Feature Freeze</li>
<li>18.04.2013: Beta</li>
<li>14.05.2013: Release</li>
</ul>
Alle anderen Zeilen werden ignoriert (jedoch wird der gesamte Beschreibungstext als Tooltip angezeigt).
</p>

<h2>Konfiguration der Termin-Schlüsselwörter</h2>

<p>Die Termin-Schlüsselwörter und deren Darstellungen im Kalender sind pro Mantis-Installation konfigurierbar. Es ist nicht möglich, die Werte auf Projektebene zu konfigurieren.</p>

<p>Die Standardwerte sind:
<ul>
<li>Alpha &rArr; α</li>
<li>Feature Freeze &rArr; ff</li>
<li>Beta &rArr; β</li>
<li>Release &rArr; r</li>
</ul>
</p>

<p>Diese können in der Datei <em>config_inc.php</em> wie folgt überschrieben werden:
<p class="code block">$version_calendar_text_map = array(
	"Wert 1" => "A",
	"Wert 2" => "B",
	"Wert 3" => "C",
);</p>
</p>

<p>Dabei ist es möglich, eine beliebige Anzahl von Schlüsselwörtern zu konfigurieren.</p>

<p>Die Konfiguration für <strong>diese</strong> Mantis-Installation ist wie folgt:
<p class="code block"><?php print_r(get_text_map()); ?></p>
</p>
	
<h2>Konfiguration des Hauptmenüs</h2>

<p>Um die oben beschriebene Arbeitsweise mit Mantis zu ermöglichen, ist es nötig, bei der Installation des Versionskalender-Plugins folgende Konfigurationen in der Datei <em>config_inc.php</em> vorzunehmen:
<p class="code block"># Version calendar plugin start
$g_default_home_page = 'plugin.php?page=VersionCalendar/version_calendar';
$g_view_changelog_threshold = ADMINISTRATOR;
$g_view_summary_threshold = ADMINISTRATOR;
# Version calendar plugin end</p>
</p>

<p>Die erste Zeile setzt die Landeseite nach der Anmeldung oder der Projektauswahl auf den Versionskalender anstatt der standardmäßig konfigurierten und meist wenig nützlichen <em>Übersicht</em>-Seite.
Die restlichen beiden Zeilen entfernen die Links zu den ebenfalls meist wenig nützlichen Seiten <em>Änderungsprotokoll</em> und <em>Zusammenfassung</em> für alle Benutzer außer dem Administrator aus dem Hauptmenü.
Der Link zur Seite <em>Roadmap</em> kann leider nicht auf diese Weise entfernt werden, da dann auch die Spalte <em>Zielversion</em> ausgeblendet wird.</p>

<h2>Plugin-Historie</h2>

<h4>Version 1.4 (17. Oktober 2013)</h4>
<ul>
<li>Responsive Webdesign: Kleinere Schriften auf kleinen Geräten</li>
</ul>

<h4>Version 1.3 (6. Mai 2013)</h4>
<ul>
<li>Links <em>Alle Einträge</em> und <em>Ungeplante Einträge</em> im Submenü</li>
</ul>

<h4>Version 1.2 (12. April 2013)</h4>
<ul>
<li>Neue Standard-Termin-Texte (Alpha, Beta)</li>
<li>Konfigurierbare Termin-Texte</li>
<li>Kommentare in Termin-Texten</li>
<li>Deutsche Monatsnamen</li>
<li>Hilfe-Seite</li>
</ul>

<h4>Version 1.1 (15. März 2013)</h4>
<ul>
<li>Konfiguration des Hauptmenüs</li>
<li>Links zur Seite <em>Einträge anzeigen</em></li>
</ul>

<h4>Version 1.0 (12. März 2013)</h4>
<ul>
<li>Erste Version</li>
</ul>
<?php

# Page footer

html_page_bottom();
