import fileinput

root = {}
stack = []
current = root

for line in fileinput.input():
    match line.strip().split():
        case ["$", "cd", "/"]:
            stack = []
            current = root
        case ["$", "cd", ".."]:
            current = stack.pop()
        case ["$", "cd", dir]:
            stack.append(current)
            current = current[dir]
        case ["$", "ls"]:
            pass
        case ["dir", dir]:
            current[dir] = {}
        case [size, filename]:
            current[filename] = int(size)
        case _:
            raise Exception

MAX = 100_000


def get_size(object: int | dict, list_of_all_sizes: list):
    if type(object) == dict:
        sum_thingy = sum(get_size(x, list_of_all_sizes) for x in object.values())
        list_of_all_sizes.append(sum_thingy)
        return sum_thingy
    return object


list_of_all_sizes = []
root_size = get_size(root, list_of_all_sizes)

print(sum(x for x in list_of_all_sizes if x <= MAX))

TOTAL_NEEDED = 30_000_000
DISK_SIZE = 70_000_000

still_needed = TOTAL_NEEDED - (DISK_SIZE - root_size)

print(min(x for x in list_of_all_sizes if x >= still_needed))
