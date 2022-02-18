<?php


namespace Tookan\DefaultValues;


class TaskStatus{
    const Assigned = 0;
    const Started = 1;
    const Successful = 2;
    const Failed = 3;
    const InProgress_Arrived = 4;
    const Unassigned = 6;
    const Accepted_Acknowledged	= 7;
    const Decline = 8;
    const Cancel = 9;
    const Deleted = 10;
}