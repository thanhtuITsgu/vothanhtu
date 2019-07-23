<?php

namespace Magenest\Movie\Block;

use Magento\Framework\View\Element\Template;

class Movie extends Template
{
    Protected $_resource;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\App\ResourceConnection $Resource)
    {
        parent::__construct($context);
        $this->_resource = $Resource;

    }

    public function getMovie()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $movieCollection = $objectManager->create('\Magenest\Movie\Model\ResourceModel\Movie\Collection');

        $tableDirector = $this->_resource->getTableName('magenest_director');
        $tableMovieActor = $this->_resource->getTableName('magenest_movie_actor');
        $tableActor = $this->_resource->getTableName('magenest_actor');
        $tableMovie = $this->_resource->getTableName('magenest_movie');
        $connection = $this->_resource->getConnection();

      /*  $sql="SELECT magenest_movie.movie_id as movie_id, magenest_movie.name as name,magenest_movie.description as description
        , magenest_movie.rating as rating, magenest_director.name as nameDirector, GROUP_CONCAT(magenest_actor.name SEPARATOR ' - ') as nameActor 
              FROM magenest_movie,magenest_actor,magenest_director,magenest_movie_actor
              WHERE magenest_movie.director_id=magenest_director.director_id
              AND magenest_movie.movie_id=magenest_movie_actor.movie_id
              AND magenest_movie_actor.actor_id=magenest_actor.actor_id
              GROUP BY magenest_movie.movie_id";*/
        $movieCollection->getSelect()->join(array('tb2' => $tableDirector),
            'main_table.director_id=tb2.director_id',
             ['tb2.name as nameDirector']
        )->join(array('mvat' => $tableMovieActor),
            'main_table.movie_id=mvat.movie_id',
             ['mvat.actor_id']
        )->join(array('at' => $tableActor),
            'mvat.actor_id=at.actor_id',
             array('at.name' => new \Zend_Db_Expr('group_concat(at.name SEPARATOR ", ")'))
        )->group('main_table.movie_id');

        return $movieCollection;

     /*   $result = $connection->fetchAll($sql);
        return $result ;*/
    }

}
