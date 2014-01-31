public class RadixSort
{
	public static void printArr(int[] arr)
	{
		System.out.println("size : "+arr.length);
		for(int i : arr)
			System.out.print(i + ", ");
		System.out.println();
	}

	public static void printArr2(int[][] arr)
	{
		System.out.println("size : "+arr.length);
		for(int i=0; i<arr.length; i++)
		{
			System.out.print(i + " : ");
			for(int j : arr[i])
				System.out.print(j + ", ");
			System.out.println();
		}
		System.out.println();
	}

	public static void main(String[] args)
	{
		int i;
        int[] arr = new int[15];
        System.out.print("original: ");
        for(i=0;i<arr.length;i++){
            arr[i] = (int)(Math.random() * 1024);        
        }
        printArr(arr);
        rSort(arr);
        System.out.print("\nsorted: ");
        printArr(arr);
        System.out.println("\nDone ;-)");
	}

	public static void rSort(int[] data)
	{
		if(data.length == 0)
			return;

		int[][] np = new int[data.length][2];
		int[] q = new int[0x100];
		//printArr2(np);
		//printArr(q);
		int i,j,k,l,f = 0;
		for(k=0; k<4; k++)
		{
			System.out.println("\nPASS ==> "+k);
			for(i=0; i<(np.length-1); i++)
				np[i][1] = i+1;			
			np[i][1] = -1;
			printArr2(np);
			for(i=0; i<q.length; i++)
				q[i] = -1;
			printArr(q);
			for(f=i=0; i<data.length; i++)
			{
				j = ((0xFF<<(k<<3))&data[i])>>(k<<3);
				System.out.println(":"+(k<<3));
				System.out.println(":"+(0xFF<<(k<<3)));
				System.out.println(":"+(data[i]>>(k<<3)));
				System.out.println(data[i] + "," +j+","+k);
				if(q[j] == -1)
					l = q[j] = f;
				else
				{
					l = q[j];
					while(np[l][1] != -1)
						l = np[l][1];
					np[l][1] = f;
					l = np[l][1];
				}
				f = np[f][1];
				np[l][0] = data[i];
				np[l][1] = -1;
			}

			for(l=q[i=j=0]; i<0x100; i++)
				for(l=q[i]; l!=-1; l=np[l][1])
					data[j++] = np[l][0];
			printArr(data);
		}
	}
}