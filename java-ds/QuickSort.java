public class QuickSort
{
	/*public static int partition(int[] arr, int l, int h)
	{
		int p = l+1;
		int v = arr[p];
		int t = arr[p]; arr[p] = arr[h]; arr[h] = t; //move pivot to end
		int si = l;
		for(int j=l; j<h; j++)
		{
			if(arr[j] < v)
			{
				int t2 = arr[j];
				arr[j] = arr[si];
				arr[si] = t2;
			}
			si++;
		}
		int t3 = arr[h];
		arr[h] = arr[si];
		arr[si] = t3;

		return si;
	}
*/
	public static void quicksort(int[] arr, int l, int h)
	{
		if (l<h)
		{
			int pidx = l;
			int p = arr[pidx];
			int lft = l; int rgt = h;
			while(lft <= rgt)
			{
				while(arr[lft] < p)
					lft += 1;
				while(arr[rgt] > p)
					rgt -= 1;
				if(lft <= rgt)
				{
					int tmp = arr[lft];
					arr[lft] = arr[rgt];
					arr[rgt] = tmp;
					lft += 1;
					rgt -= 1;
				}
			}
			printArr(arr);
			quicksort(arr, l, pidx-1);
			quicksort(arr, pidx+1, h);
		}
		return;

	}

	public static void main(String[] args)
	{
		int[] arr = {1,5,7,9,2,3,6};
		printArr(arr);
		quicksort(arr, 0, arr.length-1);		
		printArr(arr);
	}

	public static void printArr(int[] arr)
	{
		for( int i : arr )
		{
			System.out.print(i+",");
		}
		System.out.println();
	}
}