# CMAKE generated file: DO NOT EDIT!
# Generated by "Unix Makefiles" Generator, CMake Version 3.5

# Delete rule output on recipe failure.
.DELETE_ON_ERROR:


#=============================================================================
# Special targets provided by cmake.

# Disable implicit rules so canonical targets will work.
.SUFFIXES:


# Remove some rules from gmake that .SUFFIXES does not remove.
SUFFIXES =

.SUFFIXES: .hpux_make_needs_suffix_list


# Suppress display of executed commands.
$(VERBOSE).SILENT:


# A target that is always out of date.
cmake_force:

.PHONY : cmake_force

#=============================================================================
# Set environment variables for the build.

# The shell in which to execute make rules.
SHELL = /bin/sh

# The CMake executable.
CMAKE_COMMAND = /usr/bin/cmake

# The command to remove a file.
RM = /usr/bin/cmake -E remove -f

# Escaping for special characters.
EQUALS = =

# The top-level source directory on which CMake was run.
CMAKE_SOURCE_DIR = /home/syadmin/Downloads/eyeLike-master

# The top-level build directory on which CMake was run.
CMAKE_BINARY_DIR = /home/syadmin/Downloads/eyeLike-master/build

# Include any dependencies generated for this target.
include src/CMakeFiles/eyeLike.dir/depend.make

# Include the progress variables for this target.
include src/CMakeFiles/eyeLike.dir/progress.make

# Include the compile flags for this target's objects.
include src/CMakeFiles/eyeLike.dir/flags.make

src/CMakeFiles/eyeLike.dir/main.cpp.o: src/CMakeFiles/eyeLike.dir/flags.make
src/CMakeFiles/eyeLike.dir/main.cpp.o: ../src/main.cpp
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --progress-dir=/home/syadmin/Downloads/eyeLike-master/build/CMakeFiles --progress-num=$(CMAKE_PROGRESS_1) "Building CXX object src/CMakeFiles/eyeLike.dir/main.cpp.o"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++   $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -o CMakeFiles/eyeLike.dir/main.cpp.o -c /home/syadmin/Downloads/eyeLike-master/src/main.cpp

src/CMakeFiles/eyeLike.dir/main.cpp.i: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Preprocessing CXX source to CMakeFiles/eyeLike.dir/main.cpp.i"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -E /home/syadmin/Downloads/eyeLike-master/src/main.cpp > CMakeFiles/eyeLike.dir/main.cpp.i

src/CMakeFiles/eyeLike.dir/main.cpp.s: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Compiling CXX source to assembly CMakeFiles/eyeLike.dir/main.cpp.s"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -S /home/syadmin/Downloads/eyeLike-master/src/main.cpp -o CMakeFiles/eyeLike.dir/main.cpp.s

src/CMakeFiles/eyeLike.dir/main.cpp.o.requires:

.PHONY : src/CMakeFiles/eyeLike.dir/main.cpp.o.requires

src/CMakeFiles/eyeLike.dir/main.cpp.o.provides: src/CMakeFiles/eyeLike.dir/main.cpp.o.requires
	$(MAKE) -f src/CMakeFiles/eyeLike.dir/build.make src/CMakeFiles/eyeLike.dir/main.cpp.o.provides.build
.PHONY : src/CMakeFiles/eyeLike.dir/main.cpp.o.provides

src/CMakeFiles/eyeLike.dir/main.cpp.o.provides.build: src/CMakeFiles/eyeLike.dir/main.cpp.o


src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o: src/CMakeFiles/eyeLike.dir/flags.make
src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o: ../src/findEyeCenter.cpp
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --progress-dir=/home/syadmin/Downloads/eyeLike-master/build/CMakeFiles --progress-num=$(CMAKE_PROGRESS_2) "Building CXX object src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++   $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -o CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o -c /home/syadmin/Downloads/eyeLike-master/src/findEyeCenter.cpp

src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.i: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Preprocessing CXX source to CMakeFiles/eyeLike.dir/findEyeCenter.cpp.i"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -E /home/syadmin/Downloads/eyeLike-master/src/findEyeCenter.cpp > CMakeFiles/eyeLike.dir/findEyeCenter.cpp.i

src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.s: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Compiling CXX source to assembly CMakeFiles/eyeLike.dir/findEyeCenter.cpp.s"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -S /home/syadmin/Downloads/eyeLike-master/src/findEyeCenter.cpp -o CMakeFiles/eyeLike.dir/findEyeCenter.cpp.s

src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o.requires:

.PHONY : src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o.requires

src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o.provides: src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o.requires
	$(MAKE) -f src/CMakeFiles/eyeLike.dir/build.make src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o.provides.build
.PHONY : src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o.provides

src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o.provides.build: src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o


src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o: src/CMakeFiles/eyeLike.dir/flags.make
src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o: ../src/findEyeCorner.cpp
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --progress-dir=/home/syadmin/Downloads/eyeLike-master/build/CMakeFiles --progress-num=$(CMAKE_PROGRESS_3) "Building CXX object src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++   $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -o CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o -c /home/syadmin/Downloads/eyeLike-master/src/findEyeCorner.cpp

src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.i: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Preprocessing CXX source to CMakeFiles/eyeLike.dir/findEyeCorner.cpp.i"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -E /home/syadmin/Downloads/eyeLike-master/src/findEyeCorner.cpp > CMakeFiles/eyeLike.dir/findEyeCorner.cpp.i

src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.s: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Compiling CXX source to assembly CMakeFiles/eyeLike.dir/findEyeCorner.cpp.s"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -S /home/syadmin/Downloads/eyeLike-master/src/findEyeCorner.cpp -o CMakeFiles/eyeLike.dir/findEyeCorner.cpp.s

src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o.requires:

.PHONY : src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o.requires

src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o.provides: src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o.requires
	$(MAKE) -f src/CMakeFiles/eyeLike.dir/build.make src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o.provides.build
.PHONY : src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o.provides

src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o.provides.build: src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o


src/CMakeFiles/eyeLike.dir/helpers.cpp.o: src/CMakeFiles/eyeLike.dir/flags.make
src/CMakeFiles/eyeLike.dir/helpers.cpp.o: ../src/helpers.cpp
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --progress-dir=/home/syadmin/Downloads/eyeLike-master/build/CMakeFiles --progress-num=$(CMAKE_PROGRESS_4) "Building CXX object src/CMakeFiles/eyeLike.dir/helpers.cpp.o"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++   $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -o CMakeFiles/eyeLike.dir/helpers.cpp.o -c /home/syadmin/Downloads/eyeLike-master/src/helpers.cpp

src/CMakeFiles/eyeLike.dir/helpers.cpp.i: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Preprocessing CXX source to CMakeFiles/eyeLike.dir/helpers.cpp.i"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -E /home/syadmin/Downloads/eyeLike-master/src/helpers.cpp > CMakeFiles/eyeLike.dir/helpers.cpp.i

src/CMakeFiles/eyeLike.dir/helpers.cpp.s: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Compiling CXX source to assembly CMakeFiles/eyeLike.dir/helpers.cpp.s"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && /usr/bin/c++  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -S /home/syadmin/Downloads/eyeLike-master/src/helpers.cpp -o CMakeFiles/eyeLike.dir/helpers.cpp.s

src/CMakeFiles/eyeLike.dir/helpers.cpp.o.requires:

.PHONY : src/CMakeFiles/eyeLike.dir/helpers.cpp.o.requires

src/CMakeFiles/eyeLike.dir/helpers.cpp.o.provides: src/CMakeFiles/eyeLike.dir/helpers.cpp.o.requires
	$(MAKE) -f src/CMakeFiles/eyeLike.dir/build.make src/CMakeFiles/eyeLike.dir/helpers.cpp.o.provides.build
.PHONY : src/CMakeFiles/eyeLike.dir/helpers.cpp.o.provides

src/CMakeFiles/eyeLike.dir/helpers.cpp.o.provides.build: src/CMakeFiles/eyeLike.dir/helpers.cpp.o


# Object files for target eyeLike
eyeLike_OBJECTS = \
"CMakeFiles/eyeLike.dir/main.cpp.o" \
"CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o" \
"CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o" \
"CMakeFiles/eyeLike.dir/helpers.cpp.o"

# External object files for target eyeLike
eyeLike_EXTERNAL_OBJECTS =

bin/eyeLike: src/CMakeFiles/eyeLike.dir/main.cpp.o
bin/eyeLike: src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o
bin/eyeLike: src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o
bin/eyeLike: src/CMakeFiles/eyeLike.dir/helpers.cpp.o
bin/eyeLike: src/CMakeFiles/eyeLike.dir/build.make
bin/eyeLike: /usr/local/lib/libopencv_videostab.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_superres.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_stitching.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_shape.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_photo.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_objdetect.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_calib3d.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_features2d.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_ml.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_highgui.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_videoio.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_imgcodecs.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_flann.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_video.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_imgproc.so.3.1.0
bin/eyeLike: /usr/local/lib/libopencv_core.so.3.1.0
bin/eyeLike: src/CMakeFiles/eyeLike.dir/link.txt
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --bold --progress-dir=/home/syadmin/Downloads/eyeLike-master/build/CMakeFiles --progress-num=$(CMAKE_PROGRESS_5) "Linking CXX executable ../bin/eyeLike"
	cd /home/syadmin/Downloads/eyeLike-master/build/src && $(CMAKE_COMMAND) -E cmake_link_script CMakeFiles/eyeLike.dir/link.txt --verbose=$(VERBOSE)

# Rule to build all files generated by this target.
src/CMakeFiles/eyeLike.dir/build: bin/eyeLike

.PHONY : src/CMakeFiles/eyeLike.dir/build

src/CMakeFiles/eyeLike.dir/requires: src/CMakeFiles/eyeLike.dir/main.cpp.o.requires
src/CMakeFiles/eyeLike.dir/requires: src/CMakeFiles/eyeLike.dir/findEyeCenter.cpp.o.requires
src/CMakeFiles/eyeLike.dir/requires: src/CMakeFiles/eyeLike.dir/findEyeCorner.cpp.o.requires
src/CMakeFiles/eyeLike.dir/requires: src/CMakeFiles/eyeLike.dir/helpers.cpp.o.requires

.PHONY : src/CMakeFiles/eyeLike.dir/requires

src/CMakeFiles/eyeLike.dir/clean:
	cd /home/syadmin/Downloads/eyeLike-master/build/src && $(CMAKE_COMMAND) -P CMakeFiles/eyeLike.dir/cmake_clean.cmake
.PHONY : src/CMakeFiles/eyeLike.dir/clean

src/CMakeFiles/eyeLike.dir/depend:
	cd /home/syadmin/Downloads/eyeLike-master/build && $(CMAKE_COMMAND) -E cmake_depends "Unix Makefiles" /home/syadmin/Downloads/eyeLike-master /home/syadmin/Downloads/eyeLike-master/src /home/syadmin/Downloads/eyeLike-master/build /home/syadmin/Downloads/eyeLike-master/build/src /home/syadmin/Downloads/eyeLike-master/build/src/CMakeFiles/eyeLike.dir/DependInfo.cmake --color=$(COLOR)
.PHONY : src/CMakeFiles/eyeLike.dir/depend

