public class CountingSort
{
	public static void main(String[] args)
	{
		int[] data = {1, 4, 1, 9, 2, 7, 5, 2};
		int range = 10;

		printArr(data);
		int[] output = sortme(data, range);
		printArr(output);
	}

	public static int[] sortme(int[] data, int range)
	{
		int[] count = new int[range+1];
		int[] output = new int[data.length];

		for(int i=0; i<data.length; i++)
		{
			// count the number of characters/integers at that position
			count[data[i]]++;
		}

		printArr(count);

		for(int i=1; i<=range; i++)
		{
			// add previous counts to current counts. This is the position of the original char/int in the output array
			count[i] += count[i-1];
		}

		printArr(count);

		for(int i=0; i<data.length; i++)
		{
			System.out.println(i);
			System.out.println("data[i] : "+data[i]);
			System.out.println("count[data[i]] : "+count[data[i]]);

			output[count[data[i]]-1] = data[i];
			--count[data[i]];
			printArr(output);
			//System.out.println("--count[data[i]] : "+count[data[i]]);
		}

		return output;
	}

	public static void printArr(int[] arr)
	{
		for(int i: arr)
		{
			System.out.print(i + ", ");
		}
		System.out.println();
	}
}