<?php

class loadUllCourseBaseDataTask extends ullBaseTask
{
  
  protected function configure()
  {
    $this->namespace        = 'ull_course';
    $this->name             = 'load-ull-course-base-data';
    $this->briefDescription = 'Loads the basic ullCourse data like stati, permissions etc.';
    $this->detailedDescription = <<<EOF
    The [{$this->name} task|INFO] loads the basic ullCourse data like stati, permissions etc.

    Call it with:

    [php symfony {$this->namespace}:{$this->name}|INFO]
    
EOF;

    $this->addArgument('application', sfCommandArgument::OPTIONAL,
      'The application name', 'frontend');
    $this->addArgument('env', sfCommandArgument::OPTIONAL,
      'The environment', 'prod');
    
  }


  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->name, 'Initializing');
    
    $this->initializeDatabaseConnection($arguments, $options);
    
    /*
     * UllCourseStatus
     */
    
    $status = new UllCourseStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Planned';
    $status['Translation']['de']['name'] = 'Geplant';
    $status->save();
    
    $status = new UllCourseStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Announced';
    $status['Translation']['de']['name'] = 'Angekündigt';
    $status->save();
    
    $status = new UllCourseStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Active';
    $status['Translation']['de']['name'] = 'Aktiv';
    $status->save();
    
    $status = new UllCourseStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Finished';
    $status['Translation']['de']['name'] = 'Beendet';
    $status->save();
    
    $status = new UllCourseStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Insufficient participants';
    $status['Translation']['de']['name'] = 'Zu wenig Teilnehmer';
    $status->save();
    
    $status = new UllCourseStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Overbooked';
    $status['Translation']['de']['name'] = 'Überbucht';
    $status->save();
    
    $status = new UllCourseStatus;
    $status['Translation']['en']['name'] = 'Canceled';
    $status['Translation']['de']['name'] = 'Abgesagt';
    $status->save();
    
    
    /*
     * UllCourseBookingStatus
     */
    
    $status = new UllCourseBookingStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Booked';
    $status['Translation']['de']['name'] = 'Gebucht';
    $status->save();    
    
    $status = new UllCourseBookingStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Paid';
    $status['Translation']['de']['name'] = 'Bezahlt';
    $status->save();    
    
    $status = new UllCourseBookingStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Underpaid';
    $status['Translation']['de']['name'] = 'Zuwenig überwiesen';
    $status->save();    
    
    $status = new UllCourseBookingStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Overpaid';
    $status['Translation']['de']['name'] = 'Zuviel überwiesen';
    $status->save();    
    
    $status = new UllCourseBookingStatus;
    $status['namespace'] = 'ull_course';
    $status['slug'] = 'supernumerary-booked';
    $status['Translation']['en']['name'] = 'Supernumerary booking';
    $status['Translation']['de']['name'] = 'Überzählige Buchung';
    $status->save();    
    
    $status = new UllCourseBookingStatus;
    $status['namespace'] = 'ull_course';
    $status['slug'] = 'supernumerary-paid';
    $status['Translation']['en']['name'] = 'Supernumerary participant';
    $status['Translation']['de']['name'] = 'Überzähliger Teilnehmer';
    $status->save();    
    
    $status = new UllCourseBookingStatus;
    $status['namespace'] = 'ull_course';
    $status['Translation']['en']['name'] = 'Deleted';
    $status['Translation']['de']['name'] = 'Gelöscht';
    $status->save();

    /*
     * UllPermission
     */
    
    //ullCourse
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_list';
    $permission->save();
    $ull_permission_ull_course_list = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_edit';
    $permission->save();
    $ull_permission_ull_course_edit = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_delete';
    $permission->save();
    $ull_permission_ull_course_delete = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_offering';
    $permission->save();
    $ull_permission_ull_course_offering = $permission;
        
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_show';
    $permission->save();
    $ull_permission_ull_course_show = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_select_tariff';
    $permission->save();
    $ull_permission_ull_course_select_tariff = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_confirmation';
    $permission->save();
    $ull_permission_ull_course_confirmation = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_info';
    $permission->save();
    $ull_permission_ull_course_info = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_booked';
    $permission->save();
    $ull_permission_ull_course_booked = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_trainers';
    $permission->save();
    $ull_permission_ull_course_trainers = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_cancel';
    $permission->save();
    $ull_permission_ull_course_cancel = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_mail';
    $permission->save();
    $ull_permission_ull_course_mail = $permission;
    
    //ullCourseBooking
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_booking_list';
    $permission->save();
    $ull_permission_ull_course_booking_list = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_booking_edit';
    $permission->save();
    $ull_permission_ull_course_booking_edit = $permission;
    
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_course';
    $permission['slug'] = 'ull_course_booking_show';
    $permission->save();
    $ull_permission_ull_course_booking_show = $permission;
    

    /*
     * UllGroup
     */
    
    $group = new UllGroup;
    $group['namespace'] = 'ull_course';
    $group['display_name'] = 'CourseAdmins';
    $group->save();
    $ull_group_ull_course_admins = $group;    
    
    $group = new UllGroup;
    $group['namespace'] = 'ull_course';
    $group['display_name'] = 'Trainers';
    $group->save();
    $ull_group_ull_course_trainers = $group;       

    
    /*
     * UllGroupPermission
     */
    
    $ull_group_everyone = UllGroupTable::findByDisplayName('Everyone');
    $ull_group_logged_in_users = UllGroupTable::findByDisplayName('Logged in users');
    
    // everyone
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_everyone;
    $group_permission['UllPermission'] = $ull_permission_ull_course_offering;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_everyone;
    $group_permission['UllPermission'] = $ull_permission_ull_course_show;
    $group_permission->save();    
    
    // logged in 
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_logged_in_users;
    $group_permission['UllPermission'] = $ull_permission_ull_course_select_tariff;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_logged_in_users;
    $group_permission['UllPermission'] = $ull_permission_ull_course_confirmation;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_logged_in_users;
    $group_permission['UllPermission'] = $ull_permission_ull_course_booked;
    $group_permission->save();
    
    // course admin
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_ull_course_admins;
    $group_permission['UllPermission'] = $ull_permission_ull_course_list;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_ull_course_admins;
    $group_permission['UllPermission'] = $ull_permission_ull_course_edit;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_ull_course_admins;
    $group_permission['UllPermission'] = $ull_permission_ull_course_delete;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_ull_course_admins;
    $group_permission['UllPermission'] = $ull_permission_ull_course_info;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_ull_course_admins;
    $group_permission['UllPermission'] = $ull_permission_ull_course_delete;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_ull_course_admins;
    $group_permission['UllPermission'] = $ull_permission_ull_course_trainers;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_ull_course_admins;
    $group_permission['UllPermission'] = $ull_permission_ull_course_cancel;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_ull_course_admins;
    $group_permission['UllPermission'] = $ull_permission_ull_course_mail;
    $group_permission->save();
    
    // booking admin
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_ull_course_admins;
    $group_permission['UllPermission'] = $ull_permission_ull_course_booking_list;
    $group_permission->save();
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_course';
    $group_permission['UllGroup'] = $ull_group_ull_course_admins;
    $group_permission['UllPermission'] = $ull_permission_ull_course_booking_edit;
    $group_permission->save();
    
    
    // ull payment type
    $type = new UllPaymentType;
    $type['namespace'] = 'ull_core';
    $type['Translation']['en']['name'] = 'Cash';
    $type['Translation']['de']['name'] = 'Bar';
    $type->save();
    
    $type = new UllPaymentType;
    $type['namespace'] = 'ull_core';
    $type['Translation']['en']['name'] = 'Bank transfer';
    $type['Translation']['de']['name'] = 'Überweisung';
    $type->save();        
    
  }
  
  
  protected function initializeDatabaseConnection($arguments = array(), $options = array())
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration(
    $arguments['application'], $arguments['env'], true);
    
    $databaseManager = new sfDatabaseManager($configuration);

    // BE CAREFUL: gets the connection name from databases.yml not the actual db name 
    $this->dbName = Doctrine_Manager::getInstance()->getCurrentConnection()->getName();
    
//    $conn = Doctrine_Manager::connection();
  }
  
 
  
}

