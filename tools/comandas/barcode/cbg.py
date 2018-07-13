import sys

def calc_check_digit(number):
    """Calculate the EAN check digit for 13-digit numbers. The number passed
    should not have the check bit included."""
    return str((10 - sum((3, 1)[i % 2] * int(n)
                         for i, n in enumerate(reversed(number)))) % 10)

start = int(sys.argv[1])
end = int(sys.argv[2])
for x in xrange(start, end + 1):
    code = "789" + "{0:0>9}".format(x)
    print(code + calc_check_digit(code))
