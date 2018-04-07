<?php
    use Doctrine\ORM\Query\ResultSetMapping;

    function Filter()
    {
        $sql = "SELECT id FROM tag NATURAL JOIN item_tag WHERE name='cafe'";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
    }