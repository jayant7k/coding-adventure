class Node
{
	public String data;
	public Node left;
	public Node right;

	public Node(String data)
	{
		this.data = data;
		this.left = null;
		this.right = null;
	}

	public Node(String data, Node left, Node right)
	{
		this.data = data;
		this.left = left;
		this.right = right;
	}

	public void printMe()
	{
		System.out.println("D:"+this.data+",L:"+this.left.data+",R:"+this.right.data);
	}
}

class Tree
{
	public Node root;

	public Tree(Node r)
	{
		this.root = r;
	}

	public void infixParse(Node n)
	{
		if(n == null)
			return;
//		n.printMe();
		if(n.left != null)
			infixParse(n.left);
		System.out.print(n.data+",");
		if(n.right != null)
			infixParse(n.right);
	}

	public void postfixParse(Node n)
	{
		if(n == null)
			return;
		if(n.left != null)
			postfixParse(n.left);
		if(n.right != null)
			postfixParse(n.right);
		System.out.print(n.data+",");
	}

	public void prefixParse(Node n)
	{
		if(n == null)
			return;
		System.out.print(n.data+",");
		if(n.left != null)
			prefixParse(n.left);
		if(n.right != null)
			prefixParse(n.right);
	}
}

public class ParseTree
{
	public static void main(String[] args)
	{
	
		Node n4 = new Node("9");
		Node n5 = new Node("6");
		Node n6 = new Node("8");
		Node n7 = new Node("4");

		Node n2 = new Node("-",n4,n5);
		Node n3 = new Node("*",n6,n7);

		Node n1 = new Node("+",n2,n3);

		Tree t = new Tree(n1);
		//t.root.printMe();
		System.out.print("\nInfix : ");
		t.infixParse(t.root);
		System.out.print("\nPostfix : ");
		t.postfixParse(t.root);
		System.out.print("\nPrefix : ");
		t.prefixParse(t.root);
		System.out.println();
	}
}