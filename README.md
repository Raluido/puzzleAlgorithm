Developer Development task - Puzzle
This task is a complex problem to assess your abilities as a developer. It is
designed to allow you to demonstrate some skills that we are looking for in
a successful candidate, particularly, your ability to write efficient, clean,
maintainable, generic algorithms.

Exercise:
The task is to create a script that will solve a puzzle.
All the pieces of our puzzle have four sides. Each one of the sides is represented by a
positive integer. These numbers represent shapes. The number zero represents the border,
which is a special side.
For example, on these pieces, you can see sides 0 (border), 1, 2 and 3. Side 1 fits with side
1, side 2 would fit with side 2, etc. On the last images you can see how 3 pieces could fit
together.

Even though the sides 1 and 2 look similar, they are actually mirrored and that’s why they
have a different number.
There could be more than 2 pieces with the same side. That means that those pieces can fit
together.
The sides of the pieces are named sequentially, clockwise, starting with the left side. For
example, the first piece is 0 1 2 0.

The puzzle file is defined on a text file. A puzzle has a width and a height, which are defined
on the first row of the file; then, line by line represents each one of the pieces. For example,
this is a 16-piece puzzle:
4 4
2 5 4 0
2 1 4 2
0 1 1 0
4 4 0 3
0 0 4 3
0 0 1 1
1 4 0 0
4 4 3 5
5 5 2 4
1 1 0 5
4 1 0 4
1 0 2 4
3 5 1 2
1 4 2 0
0 1 5 2
1 5 0 4

Each piece is numbered sequentially, and the first piece is the piece number 1.
This is a solution to that puzzle:

3 10 16 7
15 13 2 11
12 8 9 4
6 14 1 5

The solutions are written using the piece numbers, separated by spaces, and one line per
row.
A puzzle could have multiple solutions. For example, this is also a solution for that same
puzzle:

3 12 15 6
14 8 13 10
1 9 2 16
5 4 11 7

Actually, all puzzles will have more than one solution, since you can rotate the solution
90/180/270 degrees. For the purpose of this exercise we are not interested on the rotated
solutions. With that in mind, the puzzle above has exactly two solutions. Different solutions
are represented as below, with a new line in between. These would be the solutions file for
the sample puzzle:

3 10 16 7
15 13 2 11
12 8 9 4
6 14 1 5
3 12 15 6
14 8 13 10
1 9 2 16
5 4 11 7


Rules:
● You need to use all the pieces and you can only use each piece once, (please note
that there could be two identical pieces listed)
● The border needs to be around the puzzle
● The corner pieces have to be at the corners
● You can rotate the pieces. For example, if a piece is 0 1 2 3, you can rotate it, i.e.
that piece is the same as 1 2 3 0, 2 3 0 1 and 3 0 1 2.
● You cannot flip the pieces. For example, the piece 0 1 2 3 from the previous
example is not the same as 0 3 2 1, so you cannot use it as that.
● The solution ideally needs to be written in PHP*
* Please note that for some of our vacancies, solving this task in PHP is not a requirement. If
you are not sure if you have to do it on PHP, and you prefer to solve it in a different
language, please ask us.
Your solution needs to take a file as an input (e.g. argument), and output the solution after
processing it. For example, a call from the command line, could be php solve.php
4x4.txt and will output the solutions above. Your solution doesn’t need to be contained in
a single file, it could include multiple classes / files. If you wish, you can use additional tools
as long as you call them from the main file.
Your solution will be tested with other puzzles, so you need to make sure that your algorithm
works on every scenario. As a sample, we are providing a 5x5 and a 8x8 puzzles for you to
test your script. The 5x5 puzzle has one solution, the 8x8 puzzle has 2. Your program should
provide all the solutions, not just the first one.
Please note, your solution doesn't need to have a front-end, unless you want to. A
command-line script is fine.
