<?php


namespace xZeroMCPE\Zeroify\State;


interface StateType
{
    const WAITING = "WAITING"; // Waiting for players
    const PLAYING = "PLAYING"; // When a game is currently in progress
    const ENDED = "ENDING"; // Ending stage...


    const COMPLETION = "COMPLETION";
}