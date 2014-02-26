  <?php
class ConsistentHasher
{
  private $nodeDistribution;
  private $virtualNodes;

  // nodeDistribution is the replica count per node.
  public function __construct($nodenames, $nodedistribution)
  {
    $this->nodeDistribution = $nodedistribution;
    $this->virtualNodes = array();

    for($i=0; $i<count($nodenames); $i++)
    {
      $this->addNode($nodenames[$i]);
    }
  }

  // The addNode() function takes a name and creates virtual nodes (or replicas) by 
  // appending the index of the local loop to the node name.
  // The hash value of a virtual node is an MD5 hash, base converted into an integer. 
  // The virtual node is added to a list and sorted by its value so that we ensure 
  // a lowest to highest traversal order for looking up previous and next virtual nodes
  public function addNode($name)
  {
    for($i=0; $i<$this->nodeDistribution; $i++)
    {
      //int representation of $key (8 hex chars = 4 bytes = 32 bit)
      $virtualNodeHashCode = base_convert(substr(md5($name.$i, false),0,8),16,10);
      $this->virtualNodes[$name.$i] = $virtualNodeHashCode;
    }
    asort($this->virtualNodes, SORT_NUMERIC);
  }

  public function dump()
  {
    print_r($this->virtualNodes);
    echo "\n\n";
  }

  public function sortCompare($a, $b)
  {
    if($a == $b)
    {
      return 0;
    }
    return ($a < $b) ? -1 : 1;
  }

  // The removeNode() function takes a name and removes its corresponding virtual nodes 
  // from the virtualNode list.
  // We then resort the list to ensure a lowest to highest traversal order.
  public function removeNode($name)
  {
    for($i=0; $i<$this->nodeDistribution; $i++)
    {
      unset($this->virtualNodes[$name.$i]);
    }
    asort($this->virtualNodes, SORT_NUMERIC);
  }

  // The hashToNode() function takes a key and locates the node where its value resides.
  // We loop through our virtual nodes, stopping before the first one that has a
  // hash greater than that of the key’s hash.
  // If we come to the end of the virtual node list, we loop back to the beginning to 
  // form the conceptual circle.

  public function hashToNode($key)
  {
    $keyHashCode = base_convert(substr(md5($key, false),0,8),16,10);
    $virtualNodeNames = array_keys($this->virtualNodes);
    $firstNodeName = $virtualNodeNames[0];
    $lastNodeName = $virtualNodeNames[count($virtualNodeNames)-1];
    $prevNodeName = $lastNodeName;

    foreach($this->virtualNodes as $name => $hashCode)
    {
      if($keyHashCode < $hashCode)
        return $prevNodeName;

      if($name == $lastNodeName)
        return $firstNodeName;

      $prevNodeName = $name;
    }
    return $prevNodeName;
  }
}

// demo

$hash = new ConsistentHasher(array("node1","node2","node3","node4","node5"),2);
$hash->dump();

$hash->removeNode("node2");
$hash->dump();

$hash->addNode("node6");
$hash->dump();

echo $hash->hashToNode("testing123")."\n";
echo $hash->hashToNode("key1111")."\n";
echo $hash->hashToNode("data4444")."\n";
?>
