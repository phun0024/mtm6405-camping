<?php
session_start();

/**********************************************
 * STARTER CODE
 **********************************************/

/**
 * clearSession
 * This function will clear the session.
 */
function clearSession()
{
  session_unset();
  header("Location: " . $_SERVER['PHP_SELF']);
}

/**
 * Invokes the clearSession() function.
 * This should be used if your session becomes wonky
 */
if (isset($_GET['clear'])) {
  clearSession();
}

/**
 * getResponse
 * Gets the response history array from the session and converts to a string
 * 
 * This function should be used to get the full response array as a string
 * 
 * @return string
 */
function getResponse()
{
  return implode('<br><br>', $_SESSION['camping']['response']);
}

/**
 * updateResponse
 * Adds a new response to the response array found in session
 * Returns the full response array as a string
 * 
 * This function should be used each time an action returns a response
 * 
 * @param [string] $response
 * @return string
 */
function updateResponse($response)
{
  if (!isset($_SESSION['camping'])) {
    createGameData();
  }

  array_push($_SESSION['camping']['response'], $response);

  return getResponse();
}

/**
 * help
 * Returns a formatted string of game instructions
 * 
 * @return string
 */
function help()
{
  return 'Welcome to Camping, the text based camping game. Use the following commands to play the game: <span class="red">fire</span>, <span class="red">wood</span>, <span class="red">tent</span>, <span class="red">roast</span>, <span class="red">rest</span>. To restart the game use the <span class="red">restart</span> command For these instruction again use the <span class="red">help</span> command';
}

/**********************************************
 * YOUR CODE BELOW
 **********************************************/

/**
 * createGameData
 * Create game array in Session
 */
function createGameData () {
  $_SESSION['camping'] = [
    'response' => [],
    'marshmallows' => 3,
    'wood' => 0,
    'tent' => false,
    'fire' => true
  ];

  return isset($_SESSION['camping']);
}

/**
 * tent
 * This function will set tent to true
 */
function tent () {
  $_SESSION['camping']['tent'] = true;

  return "The tent has been pitched.";
}

/**
 * wood
 * Will increase the amount of wood by 1 if the fire is not true
 */
function wood () {
  if (!$_SESSION['camping']['fire']) {
    $_SESSION['camping']['wood']++;

    return"A piece of wood was found";
  }

  return "The fire must be stopped.";
}

/**
 * fire
 * Will set the fire the false, if the fire is true
 * Will set the fire to true, if the fire is false and wood > 0
 *    Wood will decrese by 1
 */
function fire () {
  if ($_SESSION['camping']['fire']) {
    $_SESSION['camping']['fire'] = false;

    return "The fires has been stopped.";
  } elseif ($_SESSION['camping']['wood'] > 0) {
    $_SESSION['camping']['fire'] = true;
    $_SESSION['camping']['wood']--;

    return "The fire has been started";
  }

  return "There is no more wood";
}

/**
 * roast
 * Will roast a marshmallow, if fire is true
 *    Marshmallows will decrease by 1
 */
function roast () {
  if ($_SESSION['camping']['fire']) {
    if ($_SESSION['camping']['marshmallows'] > 0) {
      $_SESSION['camping']['marshmallows']--;
      return "One marshmallows has been roasted.";
    } else {
      return "There are no more marshmallows.";
    }
  } else {
    return "The fire must be going.";
  }
}

/**
 * rest
 * Will "go to sleep", if the fires is false and tent is true
 */
function rest () {
  if (!$_SESSION['camping']['fire']) {
    if ($_SESSION['camping']['tent']) {
      return "Good night!";
    } else {
      return "The tent must be pitched.";
    }
  }

  return "The fire must be stopped.";
}

/**
 * fire:
 *  invoke the fire function
 *    change the status of the fire
 *    return a response
 * 
 * variable functions
 * $func = 'help'
 * $func() ~ help()
 * 
 * function_exists
 * if (function_exists($func)) {
 *  $func()
 * }
 */

 if (isset($_POST['command'])) {
  if (function_exists($_POST['command'])) {
    updateResponse($_POST['command']());
  } else {
    updateResponse("{$_POST['command']} is not a valid command");
  }
 }